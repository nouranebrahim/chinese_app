<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sound;
use App\Models\Sentence;
use App\Models\Word;


class UserController extends Controller
{
    public function showSounds(User $user)
    {
        $sounds = $user->sounds()->withCount('sentences')->get();
        return view('users.sounds', compact('user', 'sounds'));
    }
}