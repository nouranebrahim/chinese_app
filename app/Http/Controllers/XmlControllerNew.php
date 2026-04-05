<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Sound;
use App\Models\Sentence;
use App\Models\Word;

class XmlControllerNew extends Controller
{
    public function importEbrahimXML()
    {
        $xmlPath = '/media/nouran/FAC0DA9BC0DA5E07/chinese/python/yousif_story.xml';
        $soundPath = '/media/nouran/FAC0DA9BC0DA5E07/chinese/python/praat/yousif_swr/yousif_story.wav';

        if (!File::exists($xmlPath)) {
            return response()->json(['error' => 'XML file not found.'], 404);
        }

        // Create or find the user
        $user = User::firstOrCreate(
            ['name' => 'yousif'],
            ['level' => 'intermediate', 'gender' => 'male']
        );

        // Create the sound entry
        $sound = Sound::create([
            'sound_name' => $soundPath,
            'user_id' => $user->id,
            'type' => 'narration'  //  picture_description  reading narration
        ]);

        // Load and parse XML
        $xmlContent = File::get($xmlPath);
        $xml = simplexml_load_string($xmlContent);

        foreach ($xml->sentence as $sentenceNode) {
            $start = $this->formatMicroTime((float) $sentenceNode->start);
            $end = $this->formatMicroTime((float) $sentenceNode->end);
            $subjectText = (string) $sentenceNode->text;
            $correctSentence = (string) $sentenceNode->correct_sentence_pronounciation;

            $sentence = Sentence::create([
                'sound_id' => $sound->id,
                'subject_sentence' => $subjectText,
                'correct_sentence' => $correctSentence,
                'start_time' => $start,
                'end_time' => $end,
            ]);

            foreach ($sentenceNode->word as $wordNode) {
                Word::create([
                    'subject_pronunciation'   => (string) $wordNode->subject_pronounciation,
                    'arabic_word'             => (string) $wordNode->arabic_representation,
                    'correct_pronunciation'   => (string) $wordNode->correct_word_pronounciation,
                    'phonological_errors'     => trim((string) $wordNode->phonological_processes) ?: null,
                    'notes'                   => isset($wordNode->notes) ? (string) $wordNode->notes : null,
                    'start_time'              => $this->formatMicroTime((float) $wordNode->start),
                    'end_time'                => $this->formatMicroTime((float) $wordNode->end),
                    'sentence_id'             => $sentence->id,
                    'sound_id'                => $sound->id,
                ]);
            }
        }

        return response()->json(['message' => 'XML data imported successfully.']);
    }

    private function formatMicroTime($float)
    {
        $seconds = floor($float);
        $micro = round(($float - $seconds) * 1000000);
        return gmdate("H:i:s", $seconds) . '.' . str_pad($micro, 6, '0', STR_PAD_LEFT);
    }
}
