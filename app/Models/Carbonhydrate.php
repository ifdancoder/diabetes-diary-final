<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carbonhydrate extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'carbonhydrate_type_id',
        'val',
    ];
}
