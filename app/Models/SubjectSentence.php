<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectSentence extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_sentence',
        'sentence_time_id',
    ];
}
