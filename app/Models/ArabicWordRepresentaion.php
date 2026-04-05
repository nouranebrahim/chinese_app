<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArabicWordRepresentaion extends Model
{
    use HasFactory;
    protected $fillable = [
        'arabic_word',
        'word_time_id',
        'subject_word_id',
    ];
}
