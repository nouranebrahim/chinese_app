<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectSentence extends Model
{
    use HasFactory;
    protected $fillable = [
        'correct_sentence',
        'subject_sentence_id',
    ];
}
