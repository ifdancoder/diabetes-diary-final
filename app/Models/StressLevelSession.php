<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StressLevelSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_record',
        'end_record',
        'stress_level_type_id',
    ];

    public function record() {
        return $this->belongsTo(Record::class, 'start_record');
    }

    public function stressLevelType() {
        return $this->belongsTo(StressLevelType::class, 'stress_level_type_id');
    }
}
