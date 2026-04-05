<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Request;
use App\Models\SentenceTime;
use App\Models\SubjectSentence;
use App\Models\CorrectSentence;
use App\Models\WordTime;
use App\Models\SubjectWordPronounciation;
use App\Models\ArabicWordRepresentaion;
use App\Models\CorrectWord;
use App\Models\PhonologicalWordProcess;
use App\Models\WordNote;

public function storeFromXml(Request $request)
{
    $xmlString = $request->input('xml'); // or use file: $request->file('xml')->get()
    $xml = simplexml_load_string($xmlString);

    foreach ($xml->sentence as $sentence) {
        // Sentence time
        $sentenceStart = (string) $sentence->start;
        $sentenceEnd = (string) $sentence->end;
        $sentenceTime = SentenceTime::create([
            'start_time' => $sentenceStart,
            'end_time' => $sentenceEnd
        ]);

        // Subject sentence
        $sentenceText = (string) $sentence->text;
        $subjectSentence = SubjectSentence::create([
            'subject_sentence' => $sentenceText,
            'sentence_time_id' => $sentenceTime->id
        ]);

        // Correct sentence pronunciation
        if (isset($sentence->correct_sentence_pronounciation)) {
            $correctNode = $sentence->correct_sentence_pronounciation;
            CorrectSentence::create([
                'correct_sentence' => (string) $correctNode,
                'subject_sentence_id' => $subjectSentence->id
            ]);
        }

        // Loop over <word> elements
        foreach ($sentence->word as $word) {
            $wordStart = (string) $word->start;
            $wordEnd = (string) $word->end;

            $wordTime = WordTime::create([
                'start_time' => $wordStart,
                'end_time' => $wordEnd
            ]);

            $subjectWord = SubjectWordPronounciation::create([
                'subject_word' => trim((string) $word->subject_pronounciation),
                'word_time_id' => $wordTime->id,
                'subject_sentence_id' => $subjectSentence->id
            ]);

            if ((string) $word->arabic_representation !== '-') {
                ArabicWordRepresentaion::create([
                    'arabic_word' => (string) $word->arabic_representation,
                    'word_time_id' => $wordTime->id,
                    'subject_word_id' => $subjectWord->id
                ]);
            }

            if ((string) $word->correct_word_pronounciation !== '-') {
                CorrectWord::create([
                    'correct_word' => trim((string) $word->correct_word_pronounciation),
                    'subject_word_id' => $subjectWord->id
                ]);
            }

            if ((string) $word->phonological_processes !== '-') {
                PhonologicalWordProcess::create([
                    'phonological_word_process' => (string) $word->phonological_processes,
                    'subject_word_id' => $subjectWord->id
                ]);
            }

            if (isset($word->notes)) {
                WordNote::create([
                    'word_note' => (string) $word->notes,
                    'subject_word_id' => $subjectWord->id
                ]);
            }
        }
    }

    return response()->json(['message' => 'Data inserted successfully']);
}
