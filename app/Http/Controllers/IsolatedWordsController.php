<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Sound;
use App\Models\Word;

class IsolatedWordsController extends Controller
{
    //
    public function importIsolatedWords()
    {
        // Path to XML file
        $xmlPath = '/media/nouran/FAC0DA9BC0DA5E07/chinese/python/isolated_words_ebrahim_reading.xml';
        $soundId = 1; // Change this to your actual sound_id

        if (!File::exists($xmlPath)) {
            return response()->json(['error' => 'XML file not found.'], 404);
        }

        // Find sound
        $sound = Sound::find($soundId);
        if (!$sound) {
            return response()->json(['error' => 'Sound not found.'], 404);
        }

        // Parse XML
        $xmlContent = File::get($xmlPath);
        $xml = simplexml_load_string($xmlContent);

        $count = 0;
        foreach ($xml->isolated_word as $wordNode) {
            Word::create([
                'subject_pronunciation'   => (string) $wordNode->subject_pronounciation,
                'arabic_word'             => (string) $wordNode->arabic_representation,
                'correct_pronunciation'   => (string) $wordNode->correct_word_pronounciation,
                'phonological_errors'     => trim((string) $wordNode->phonological_processes) ?: null,
                'notes'                   => isset($wordNode->notes) ? (string) $wordNode->notes : null,
                'start_time'              => $this->formatMicroTime((float) $wordNode->start),
                'end_time'                => $this->formatMicroTime((float) $wordNode->end),
                'sentence_id'             => null,
                'sound_id'                => $sound->id,
            ]);
            $count++;
        }

        return response()->json([
            'message' => "Imported {$count} isolated words for sound '{$sound->sound_name}'"
        ]);
    }

    private function formatMicroTime($float)
    {
        $seconds = floor($float);
        $micro = round(($float - $seconds) * 1000000);
        return gmdate("H:i:s", $seconds) . '.' . str_pad($micro, 6, '0', STR_PAD_LEFT);
    }
}
