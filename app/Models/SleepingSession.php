<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_record',
        'end_record',
    ];
}
