<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectWord extends Model
{
    use HasFactory;
    protected $fillable = [
        'correct_word',
        'correct_sentence_id',
        'subject_word_id',
    ];
}
