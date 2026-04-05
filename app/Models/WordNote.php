<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'word_note',
        'subject_word_id',
    ];
}
