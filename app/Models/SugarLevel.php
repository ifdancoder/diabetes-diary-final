<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugarLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'sugar_level_type_id',
        'val',
    ];
}
