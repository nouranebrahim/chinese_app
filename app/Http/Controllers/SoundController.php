<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sound;
use App\Models\Sentence;
use App\Models\Word;


class SoundController extends Controller
{
    public function show($id)
    {
        $sound = Sound::with(['sentences.words'])->findOrFail($id);
    
        // Get isolated words (words with sentence_id = null)
        $isolatedWords = $sound->words()->whereNull('sentence_id')->orderBy('id')->get();
    
        return view('sounds.show', compact('sound', 'isolatedWords'));
    }
}
