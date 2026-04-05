<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectWordPronounciation extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_word',
        'word_time_id',
        'subject_sentence_id',
    ];
}
