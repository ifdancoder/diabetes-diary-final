<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PhysicalActivityType;

class PhysicalActivitySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_record',
        'end_record',
        'physical_activity_type_id',
    ];

    public function record() {
        return $this->belongsTo(Record::class, 'start_record');
    }
    public function physicalActivityType() {
        return $this->belongsTo(PhysicalActivityType::class, 'physical_activity_type_id');
    }
}
