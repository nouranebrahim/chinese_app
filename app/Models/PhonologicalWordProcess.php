<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonologicalWordProcess extends Model
{
    use HasFactory;
    protected $fillable = [
        'phonological_word_process',
        'subject_word_id',
    ];
}
