<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporalBasalVelocity extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'interval',
        'percentage',
    ];

    public function record() {
        return $this->belongsTo(Record::class);
    }

    public function remains($current_datetime) {
        $record = $this->record;
        $record_datetime = $record->datetime;

        $carbon_interval = \Carbon\Carbon::parse($this->interval);
        $record_datetime = \Carbon\Carbon::parse($record_datetime);
        $end_datetime = $record_datetime->addHours($carbon_interval->hour)->addMinutes($carbon_interval->minute)->addSeconds($carbon_interval->second);
        
        $time = null;
        if ($current_datetime < $end_datetime) {
            $diff = $end_datetime->diff($current_datetime);
            $time = $diff->format('%H:%I:%S');
        }

        return $current_datetime < $end_datetime ? $time : null;
    }
}
