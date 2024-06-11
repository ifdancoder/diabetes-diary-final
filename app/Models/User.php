<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use App\Models\PersonalSettings;
use App\Models\Timezone;
use App\Models\BasalValues;
use App\Models\Record;
use App\Models\Experiment;
use Carbon\Carbon;
use DB;

use App\Traits\DatetimeTrait;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, DatetimeTrait, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'tg_id',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function personalSettings()
    {
        return $this->hasOne(PersonalSettings::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function experiments()
    {
        return $this->HasMany(Experiment::class);
    }

    public function experiment()
    {
        return $this->belongsTo(Experiment::class, 'experiment_id');
    }

    public function userRecords()
    {
        $records = $this->records()->where(function ($query) {
            $query->where(function ($query) {
                $query->doesntHave('sugarLevels')
                    ->orWhereHas('sugarLevels', function ($query) {
                        $query->where('sugar_level_type_id', '=', 2);
                    });
            })
                ->orWhere(function ($query) {
                    $query->has('basalValues')
                        ->orHas('carbonhydrates')
                        ->orHas('insulinInjections')
                        ->orHas('physicalActivitySession')
                        ->orHas('stressLevelSession')
                        ->orHas('temporalBasalVelocity')
                        ->orHas('sleepingSession')
                        ->orHas('deseaseSession')
                        ->orHas('cannulaChanging');
                });
        })
            ->where('records.record_type_id', '=', 1)
            ->orderBy('records.datetime', 'desc')
            ->get();

        return $records;
    }

    public function userRecordsInInterval($from, $to)
    {
        $records = $this->records()->where(function ($query) {
            $query->where(function ($query) {
                $query->doesntHave('sugarLevels')
                    ->orWhereHas('sugarLevels', function ($query) {
                        $query->where('sugar_level_type_id', '=', 2);
                    });
            })
                ->orWhere(function ($query) {
                    $query->has('basalValues')
                        ->orHas('carbonhydrates')
                        ->orHas('insulinInjections')
                        ->orHas('physicalActivitySession')
                        ->orHas('stressLevelSession')
                        ->orHas('temporalBasalVelocity')
                        ->orHas('sleepingSession')
                        ->orHas('deseaseSession')
                        ->orHas('cannulaChanging');
                });
        })
            ->where('records.record_type_id', '=', 1)
            ->whereBetween('records.datetime', [$from, $to])
            ->orderBy('records.datetime', 'asc')
            ->get();
        return $records;
    }

    public function experimentUserRecordsInInterval($experiment_id, $from, $to)
    {
        $records = $this->records()->where(function ($query) use ($experiment_id) {
            $query->doesntHave('experiments')
                ->orWhereHas('experiments', function ($query) use ($experiment_id) {
                    $query->where('experiments.id', '=', $experiment_id);
                });
        })
            ->where('records.record_type_id', '!=', 3)
            ->whereBetween('records.datetime', [$from, $to])
            ->orderBy('records.datetime', 'asc')
            ->get();
        return $records;
    }

    public function experimentUserRecordByDatetime($experiment_id, $datetime)
    {
        $record = $this->records()->where(function ($query) use ($experiment_id) {
            $query->doesntHave('experiments')
                ->orWhereHas('experiments', function ($query) use ($experiment_id) {
                    $query->where('experiments.id', '=', $experiment_id);
                });
        })
            ->where('records.record_type_id', '!=', 3)
            ->where('records.datetime', '=', $datetime)
            ->orderBy('records.datetime', 'asc')
            ->get();
        return $record->first();
    }

    public function activeBasalValuesByDatetime($datetime)
    {
        $record = $this->records()->where(function ($query) use ($datetime) {
            $query->has('basalValues');
        })->where('records.datetime', '<=', $datetime)
            ->orderBy('records.datetime', 'desc')
            ->first();
        return $record->basalValues;
    }

    public function getSugarLevels()
    {
        $records = DB::table('records')
            ->join('sugar_levels', 'records.id', '=', 'sugar_levels.record_id')
            ->select('records.datetime', 'sugar_levels.*')
            ->where('records.user_id', $this->id)
            ->where('sugar_levels.sugar_level_type_id', 2)
            ->orderBy('records.datetime', 'asc')
            ->get();
        return $records;
    }

    public function getCGMSugarLevels()
    {
        $records = DB::table('records')
            ->join('sugar_levels', 'records.id', '=', 'sugar_levels.record_id')
            ->select('records.datetime', 'sugar_levels.*')
            ->where('records.user_id', $this->id)
            ->where('sugar_levels.sugar_level_type_id', 1)
            ->orderBy('records.datetime', 'asc')
            ->get();

        return $records;
    }

    public function getNearSugarLevels($datetime)
    {
        $hourInterval = 12;

        $end_datetime = Carbon::parse($datetime)->addHours($hourInterval);
        $start_datetime = Carbon::parse($datetime)->subHours($hourInterval);

        $records = DB::table('records')
            ->join('sugar_levels', 'records.id', '=', 'sugar_levels.record_id')
            ->select('records.datetime', 'records.timezone_id', 'sugar_levels.*')
            ->where('records.user_id', $this->id)
            ->where('sugar_levels.sugar_level_type_id', 2)
            ->where('records.datetime', '>=', $start_datetime)
            ->where('records.datetime', '<=', $end_datetime)
            ->orderBy('records.datetime', 'asc')
            ->get();

        $record = $records->map(function ($record) {
            $record->datetime = $this->showDatetime($record);
            return $record;
        });

        return $record;
    }

    public function getNearCGMSugarLevels($datetime)
    {
        $hourInterval = 12;

        $end_datetime = Carbon::parse($datetime)->addHours($hourInterval);
        $start_datetime = Carbon::parse($datetime)->subHours($hourInterval);

        $records = DB::table('records')
            ->join('sugar_levels', 'records.id', '=', 'sugar_levels.record_id')
            ->select('records.datetime', 'records.timezone_id', 'sugar_levels.*')
            ->where('records.user_id', $this->id)
            ->where('sugar_levels.sugar_level_type_id', 1)
            ->where('records.datetime', '>=', $start_datetime)
            ->where('records.datetime', '<=', $end_datetime)
            ->orderBy('records.datetime', 'asc')
            ->get();


        $record = $records->map(function ($record) {
            $record->datetime = $this->showDatetime($record);
            return $record;
        });

        return $record;
    }

    public function getNearPredictedSugarLevels($datetime, $experiment_id)
    {
        $hourInterval = 12;

        $end_datetime = Carbon::parse($datetime)->addHours($hourInterval);
        $start_datetime = Carbon::parse($datetime)->subHours($hourInterval);

        $records = DB::table('records')
            ->join('sugar_levels', 'records.id', '=', 'sugar_levels.record_id')
            ->join('experiments_records', 'records.id', '=', 'experiments_records.record_id')
            ->where('experiments_records.experiment_id', '=', $experiment_id)
            ->select('records.datetime', 'records.timezone_id', 'sugar_levels.*')
            ->where('records.user_id', $this->id)
            ->where('sugar_levels.sugar_level_type_id', 3)
            ->where('records.datetime', '>=', $start_datetime)
            ->where('records.datetime', '<=', $end_datetime)
            ->orderBy('records.datetime', 'asc')
            ->get();

        $record = $records->map(function ($record) {
            $record->datetime = $this->showDatetime($record);
            return $record;
        });

        return $record;
    }

    public function showDatetime($record)
    {
        $timezone = Timezone::find($record->timezone_id);
        return $this->personalSettings->show_datetime_type ? $this->recordTimezoneDatetime($record->datetime, $timezone->timezone_name) : $this->userTimezoneDatetime($record->datetime);
    }

    public function userTimezoneDatetime($datetime)
    {
        return $this->UTCtoTimezone($datetime, $this->personalSettings->timezone->timezone_name);
    }

    public function recordTimezoneDatetime($datetime, $timezone_name)
    {
        return $this->UTCtoTimezone($datetime, $timezone_name);
    }

    public function getRecordByDatetime($datetime)
    {
        $current_record = $this->records()
            ->where('datetime', '=', $datetime)
            ->where('record_type_id', 1)
            ->first();
        if ($current_record) {
            return $current_record;
        } else {
            $record = new Record();
            $record->user_id = $this->id;
            $record->datetime = $datetime;
            $record->timezone_id = $this->personalSettings->timezone_id;
            return $record;
        }
    }
    public function getExperimentRecordByDatetime($datetime, $experiment_id)
    {
        $current_record = $this->records()->where(function ($query) use ($experiment_id) {
            $query->doesntHave('experiments')
                ->orWhereHas('experiments', function ($query) use ($experiment_id) {
                    $query->where('experiments.id', '=', $experiment_id);
                });
        })
            ->where('datetime', '=', $datetime)
            ->where('record_type_id', 2)
            ->first();
        if ($current_record) {
            return $current_record;
        } else {
            $record = new Record();
            $record->user_id = $this->id;
            $record->datetime = $datetime;
            $record->record_type_id = 2;
            $record->timezone_id = $this->personalSettings->timezone_id;
            return $record;
        }
    }

    public function getPredictedRecordByDatetime($datetime, $experiment_id)
    {
        $current_record = $this->records()->where(function ($query) use ($experiment_id) {
            $query->doesntHave('experiments')
                ->orWhereHas('experiments', function ($query) use ($experiment_id) {
                    $query->where('experiments.id', '=', $experiment_id);
                });
        })
            ->where('datetime', '=', $datetime)
            ->where('record_type_id', 3)
            ->first();
        if ($current_record) {
            return $current_record;
        } else {
            $record = new Record();
            $record->user_id = $this->id;
            $record->datetime = $datetime;
            $record->record_type_id = 3;
            $record->timezone_id = $this->personalSettings->timezone_id;
            return $record;
        }
    }

    public function getBasalValuesAt($datetime)
    {
        return $this->records()->join('basal_values', 'basal_values.record_id', '=', 'records.id')->where('records.datetime', '<=', $datetime)->first();
    }
    public function basalChangings()
    {
        return $this->hasMany(Record::class, 'user_id', 'id')
            ->whereIn('id', function ($query) {
                $query->select('record_id')
                    ->from('basal_values')
                    ->where('user_id', $this->id);
            });
    }
    public function basalChangingsAtDatetime($datetime)
    {
        return $this->basalChangings()->where('records.datetime', '<=', $datetime)->orderBy('datetime', 'desc')->first();
    }

    public function getDatetime()
    {
        return $this->UTCtoTimezone(Carbon::now(), $this->personalSettings->timezone->timezone_name);
    }
    public function getDatetimeLocal()
    {
        return $this->UTCtoTimezoneLocal(Carbon::now(), $this->personalSettings->timezone->timezone_name);
    }
    public function getRecordsDates()
    {
        $date_strings = [];
        foreach ($this->userRecords() as $record) {
            $datetime_string = $this->showDatetime($record);
            $date_strings[] = Carbon::parse($datetime_string)->format('Y-m-d');
        }
        return array_unique($date_strings);
    }

    public function getRecordsByDate($date)
    {
        $dateInUTC = Carbon::createFromFormat('Y-m-d', $date, $this->personalSettings->timezone->timezone_name);
        $startOfDay = $dateInUTC->copy()->startOfDay()->setTimezone('UTC');
        $endOfDay = $dateInUTC->copy()->endOfDay()->setTimezone('UTC');

        $records = $this->userRecordsInInterval($startOfDay, $endOfDay);

        return $records;
    }

    public function getNearestSugarLevelCGMDatetimeByDatetime($datetime)
    {
        $records = DB::table('records')
            ->join('sugar_levels', 'records.id', '=', 'sugar_levels.record_id')
            ->select('records.datetime', 'records.timezone_id', 'sugar_levels.*')
            ->where('records.user_id', $this->id)
            ->where('sugar_levels.sugar_level_type_id', 1)
            ->where('records.datetime', '<=', $datetime)
            ->orderBy('records.datetime', 'desc')
            ->get();

        return $records->first()->datetime;
    }
}
