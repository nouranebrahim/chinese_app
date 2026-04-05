<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sound;
use App\Models\Sentence;
use App\Models\Word;

class LevelController extends Controller
{
    public function index()
    {
        $levels = User::select('level')->distinct()->pluck('level');
        return view('levels.index', compact('levels'));
    }

    public function showUsers($level)
    {
        $users = User::where('level', $level)->get(); // ✅ Load the users
    
        return view('levels.users', [
            'users' => $users,
            'level' => $level
        ]);
    }
}