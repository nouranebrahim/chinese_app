<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
    use HasFactory;

    protected $fillable = [
        'sound_id',
        'subject_sentence',
        'correct_sentence',
        'start_time',
        'end_time'
    ];

    public function sound()
    {
        return $this->belongsTo(Sound::class);
    }

    public function words()
    {
        return $this->hasMany(Word::class);
    }
    // Mutator for start_time
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $this->convertToMicroTime($value);
    }
    
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $this->convertToMicroTime($value);
    }
    
    private function convertToMicroTime($value)
    {
        // If already in format "HH:MM:SS.uuuuuu", return as-is
        if (is_string($value) && preg_match('/^\d{2}:\d{2}:\d{2}\.\d+$/', $value)) {
            return $value;
        }
    
        // If it's a float or numeric string, convert to microtime string
        if (is_numeric($value)) {
            $float = (float) $value;
            $seconds = floor($float);
            $micro = round(($float - $seconds) * 1000000);
            return gmdate("H:i:s", $seconds) . '.' . str_pad($micro, 6, '0', STR_PAD_LEFT);
        }
    
        // Otherwise, fallback to null or throw error
        return null;
    }
}
