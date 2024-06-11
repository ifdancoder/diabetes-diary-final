<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Period;
use App\Models\Record;

class BasalValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'val',
        'period_id',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
