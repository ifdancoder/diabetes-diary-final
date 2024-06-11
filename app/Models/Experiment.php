<?php

namespace App\Models;

use App\Traits\DatetimeTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    use HasFactory, DatetimeTrait;

    protected $fillable = [
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function formattedCreatedAt()
    {
        return $this->UTCtoTimezone($this->created_at, $this->user->personalSettings->timezone->timezone_name);
    }

    public function records()
    {
        return $this->belongsToMany(Record::class, 'experiments_records', 'experiment_id', 'record_id')->where('records.record_type_id', '!=', 3)->withTimestamps();
    }

    public function predictedRecords()
    {
        return $this->belongsToMany(Record::class, 'experiments_records', 'experiment_id', 'record_id')->where('records.record_type_id', '=', 3)->withTimestamps();
    }
}
