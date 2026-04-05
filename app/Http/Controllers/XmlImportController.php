<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\Models\Sentence;
use App\Models\Word;
use App\Models\Sound;
use Illuminate\Http\Request;

class XmlImportController extends Controller
{
    public function importFromXml()
    {
        // مسار ملف الـ XML
        $xmlPath = '/media/nouran/FAC0DA9BC0DA5E07/chinese/python/combined_youssif_story.xml';
        $soundId = 33; // عدل حسب الـ sound الموجود عندك

        if (!File::exists($xmlPath)) {
            return response()->json(['error' => 'XML file not found'], 404);
        }

        // تحميل XML
        $xmlContent = File::get($xmlPath);
        $xml = simplexml_load_string($xmlContent);

        if (!$xml) {
            return response()->json(['error' => 'Invalid XML file'], 400);
        }

        $sentencesCount = 0;
        $wordsCount = 0;
        $isolatedCount = 0;

        // 1️⃣ استيراد الجمل والكلمات
        foreach ($xml->sentence as $sentenceNode) {
            $sentence = Sentence::create([
                'start_time' => (string) $sentenceNode->start,
                'end_time' => (string) $sentenceNode->end,
                'subject_sentence' => (string) $sentenceNode->text,
                'correct_sentence' => (string) $sentenceNode->correct_sentence_pronounciation,
                'sound_id' => $soundId,
            ]);
            $sentencesCount++;

            // إدخال الكلمات داخل الجملة
            foreach ($sentenceNode->word as $wordNode) {
                Word::create([
                    'subject_pronunciation' => (string) $wordNode->subject_pronounciation,
                    'arabic_word' => (string) $wordNode->arabic_representation,
                    'correct_pronunciation' => (string) $wordNode->correct_word_pronounciation,
                    'phonological_errors' => trim((string) $wordNode->phonological_processes) ?: null,
                    'notes' => isset($wordNode->notes) ? (string) $wordNode->notes : null,
                    'start_time' => (string) $wordNode->start,
                    'end_time' => (string) $wordNode->end,
                    'sentence_id' => $sentence->id,
                    'sound_id' => $soundId,
                ]);
                $wordsCount++;
            }
        }

        // 2️⃣ استيراد الكلمات المعزولة
        foreach ($xml->isolated_word as $isoNode) {
            Word::create([
                'subject_pronunciation' => (string) $isoNode->subject_pronounciation,
                'arabic_word' => (string) $isoNode->arabic_representation,
                'correct_pronunciation' => (string) $isoNode->correct_word_pronounciation,
                'phonological_errors' => trim((string) $isoNode->phonological_processes) ?: null,
                'notes' => isset($isoNode->notes) ? (string) $isoNode->notes : null,
                'start_time' => (string) $isoNode->start,
                'end_time' => (string) $isoNode->end,
                'sentence_id' => null, // مفيش جملة
                'sound_id' => $soundId,
            ]);
            $isolatedCount++;
        }

        return response()->json([
            'message' => "Imported $sentencesCount sentences, $wordsCount words, and $isolatedCount isolated words for sound ID $soundId"
        ]);
    }
}