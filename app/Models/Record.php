<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DatetimeTrait;

use App\Models\BasalValue;
use App\Models\PhysicalActivitySession;
use App\Models\StressLevelSession;
use App\Models\InsulinInjection;
use App\Models\SleepingSession;
use App\Models\DeseaseSession;
use App\Models\CannulaChanging;
use App\Models\TemporalBasalVelocity;
use App\Models\SugarLevel;
use App\Models\Carbonhydrate;

use Carbon\Carbon;

use DB;

class Record extends Model
{
    use HasFactory, DatetimeTrait;

    protected $fillable = [
        'datetime',
        'user_id',
        'timezone_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class);
    }

    public function basalValues()
    {
        return $this->hasMany(BasalValue::class);
    }

    public function userTimezoneDatetime()
    {
        return $this->UTCtoTimezone($this->datetime, $this->user->personalSettings->timezone->timezone_name);
    }

    public function recordTimezoneDatetime()
    {
        return $this->UTCtoTimezone($this->datetime, $this->timezone->timezone_name);
    }

    public function showDatetime()
    {
        return $this->user->personalSettings->show_datetime_type ? $this->recordTimezoneDatetime() : $this->userTimezoneDatetime();
    }

    public function formattedShowDatetime()
    {
        $datetime = $this->user->personalSettings->show_datetime_type ? $this->recordTimezoneDatetime() : $this->userTimezoneDatetime();
        $format = 'Y-m-d\TH:i';
        return Carbon::parse($datetime)->format($format);
    }

    public function physicalActivitySession()
    {
        return $this->hasOne(PhysicalActivitySession::class, 'start_record');
    }

    public function activePhysicalActivitySession()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;
        $previousPhysicalActivitySessionForThatDatetime = DB::table('records')
            ->join('physical_activity_sessions', 'physical_activity_sessions.start_record', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '<', $thisDatetime)
            ->orderBy('records.datetime', 'desc')
            ->select('physical_activity_sessions.*', 'records.datetime')
            ->first();
        $previousPhysicalActivitySession = $previousPhysicalActivitySessionForThatDatetime ? PhysicalActivitySession::find($previousPhysicalActivitySessionForThatDatetime->id) : null;
        return $previousPhysicalActivitySession;
    }

    public function nextPhysicalActivitySession()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;

        $nextPhysicalActivitySessionForThatDatetime = DB::table('records')
            ->join('physical_activity_sessions', 'physical_activity_sessions.start_record', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '>', $thisDatetime)
            ->orderBy('records.datetime', 'asc')
            ->select('physical_activity_sessions.*', 'records.datetime')
            ->first();

        $nextPhysicalActivitySession = $nextPhysicalActivitySessionForThatDatetime ? PhysicalActivitySession::find($nextPhysicalActivitySessionForThatDatetime->id) : null;

        return $nextPhysicalActivitySession;
    }

    public function stressLevelSession()
    {
        return $this->hasOne(StressLevelSession::class, 'start_record');
    }

    public function activeStressLevelSession()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;
        $previousStressLevelSessionForThatDatetime = DB::table('records')
            ->join('stress_level_sessions', 'stress_level_sessions.start_record', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '<', $thisDatetime)
            ->orderBy('records.datetime', 'desc')
            ->select('stress_level_sessions.*', 'records.datetime')
            ->first();

        $previousStressLevelSession = $previousStressLevelSessionForThatDatetime ? StressLevelSession::find($previousStressLevelSessionForThatDatetime->id) : null;
        
        return $previousStressLevelSession;
    }

    public function nextStressLevelSession()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;

        $nextStressLevelSessionForThatDatetime = DB::table('records')
            ->join('stress_level_sessions', 'stress_level_sessions.start_record', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '>', $thisDatetime)
            ->orderBy('records.datetime', 'asc')
            ->select('stress_level_sessions.*', 'records.datetime')
            ->first();

        $nextStressLevelSession = $nextStressLevelSessionForThatDatetime ? StressLevelSession::find($nextStressLevelSessionForThatDatetime->id) : null;

        return $nextStressLevelSession;
    }
    public function sleepingSession()
    {
        return $this->hasOne(SleepingSession::class, 'start_record');
    }
    public function activeSleepingSession()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;
        $previousSleepingSessionForThatDatetime = DB::table('records')
            ->join('sleeping_sessions', 'sleeping_sessions.start_record', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '<', $thisDatetime)
            ->orderBy('records.datetime', 'desc')
            ->select('sleeping_sessions.*', 'records.datetime')
            ->first();

        $previousSleepingSession = $previousSleepingSessionForThatDatetime ? SleepingSession::find($previousSleepingSessionForThatDatetime->id) : null;
        if (!$previousSleepingSession) {
            return null;
        } else {
            if ($previousSleepingSession->end_record) {
                $end_record_by_its_id = DB::table('records')->where('id', $previousSleepingSessionForThatDatetime->end_record)->first();
                if ($end_record_by_its_id->datetime < $thisDatetime) {
                    return null;
                } else {
                    return $previousSleepingSession;
                }
            } else {
                return $previousSleepingSession;
            }
        }
    }

    public function cannulaChanging()
    {
        return $this->hasOne(CannulaChanging::class);
    }
    public function carbonhydrates()
    {
        return $this->hasMany(Carbonhydrate::class);
    }
    public function fastCarbonhydrate() {
        return $this->carbonHydrates()->where('carbonhydrate_type_id', 1)->first();
    }
    public function middleCarbonhydrate() {
        return $this->carbonHydrates()->where('carbonhydrate_type_id', 2)->first();
    }
    public function slowCarbonhydrate() {
        return $this->carbonHydrates()->where('carbonhydrate_type_id', 3)->first();
    }
    public function insulinInjections()
    {
        return $this->hasMany(InsulinInjection::class);
    }
    public function bolusInjection() {
        return $this->insulinInjections()->where('interval', '=', '00:00')->first();
    }
    public function prolongedInjection() {
        return $this->insulinInjections()->where('interval', '!=', '00:00')->first();
    }
    public function deseaseSession()
    {
        return $this->hasOne(DeseaseSession::class, 'start_record');
    }
    public function activeDeseaseSession()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;
        $previousDeseaseSessionForThatDatetime = DB::table('records')
            ->join('desease_sessions', 'desease_sessions.start_record', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '<', $thisDatetime)
            ->orderBy('records.datetime', 'desc')
            ->select('desease_sessions.*', 'records.datetime')
            ->first();

        $previousDeseaseSession = $previousDeseaseSessionForThatDatetime ? DeseaseSession::find($previousDeseaseSessionForThatDatetime->id) : null;
        if (!$previousDeseaseSession) {
            return null;
        } else {
            if ($previousDeseaseSession->end_record) {
                $end_record_by_its_id = DB::table('records')->where('id', $previousDeseaseSessionForThatDatetime->end_record)->first();
                if ($end_record_by_its_id->datetime < $thisDatetime) {
                    return null;
                } else {
                    return $previousDeseaseSession;
                }
            } else {
                return $previousDeseaseSession;
            }
        }
    }
    public function temporalBasalVelocity()
    {
        return $this->hasOne(TemporalBasalVelocity::class);
    }
    public function activeTemporalBasalVelocity()
    {
        $thisDatetime = $this->datetime;
        $user_id = $this->user_id;
        $previousTemporalBasalVelocityForThatDatetime = DB::table('records')
            ->join('temporal_basal_velocities', 'temporal_basal_velocities.record_id', '=', 'records.id')
            ->where('user_id', $user_id)
            ->where('records.datetime', '<', $thisDatetime)
            ->orderBy('records.datetime', 'desc')
            ->select('temporal_basal_velocities.*', 'records.datetime')
            ->first();

        $previousTemporalBasalVelocity = $previousTemporalBasalVelocityForThatDatetime ? TemporalBasalVelocity::find($previousTemporalBasalVelocityForThatDatetime->id) : null;
        
        if (!$previousTemporalBasalVelocity) {
            return null;
        } else {
            if (is_null($previousTemporalBasalVelocity->remains($thisDatetime))) {
                return null;
            } else {
                return $previousTemporalBasalVelocity;
            }
        }
    }
    public function sugarLevels()
    {
        return $this->hasMany(SugarLevel::class);
    }
    public function sugarLevel()
    {
        return $this->hasOne(SugarLevel::class)->where('sugar_level_type_id', 2);
    }
    public function sugarLevelCGM()
    {
        return $this->hasOne(SugarLevel::class)->where('sugar_level_type_id', 1);
    }

    public function experiments(){
        return $this->belongsToMany(Experiment::class, 'experiments_records', 'record_id', 'experiment_id');
    }
}