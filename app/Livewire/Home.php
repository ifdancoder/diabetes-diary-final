<?php

namespace App\Livewire;

use Livewire\Component;

use App\Traits\DatetimeTrait;
use App\Models\CarbonhydrateType;
use App\Models\PhysicalActivityType;
use App\Models\StressLevelType;

use Carbon\Carbon;

use App\Models\SugarLevel;
use App\Models\InsulinInjection;
use App\Models\Carbonhydrate;
use App\Models\PhysicalActivitySession;
use App\Models\StressLevelSession;
use App\Models\SleepingSession;
use App\Models\DeseaseSession;
use App\Models\CannulaChanging;
use App\Models\TemporalBasalVelocity;

class Home extends Component
{
    use DatetimeTrait;

    protected $listeners = ['actualizeChartData'];

    public $user;
    public $user_timezone_id;
    public $user_timezone_name;
    public $show_datetime_type;

    public $carbonhydrate_types;
    public $physical_activity_types;
    public $stress_level_types;


    public $record;
    public $formatted_current_datetime;


    public $sugar_level;

    public $fast_carbonhydrates;
    public $middle_carbonhydrates;
    public $slow_carbonhydrates;

    public $bolus;
    public $prolonged_bolus;

    public $physical_activity_session;
    public $active_physical_activity_session;
    public $next_physical_activity_session;
    public $stress_level_session;
    public $active_stress_level_session;
    public $next_stress_level_session;
    public $sleeping_session;
    public $active_sleeping_session;
    public $desease_session;
    public $active_desease_session;

    public $temporal_basal_velocity;
    public $active_temporal_basal_velocity;
    public $cannula_changing_status;


    public $sugar_level_value;

    public $fast_carbonhydrates_value;
    public $middle_carbonhydrates_value;
    public $slow_carbonhydrates_value;

    public $bolus_value;
    public $prolonged_bolus_value;
    public $prolonged_bolus_interval;

    public $physical_activity_session_value;
    public $stress_level_session_value;
    public $sleeping_session_value;
    public $desease_session_value;
    public $cannula_changing_status_value;

    public $temporal_basal_velocity_value;
    public $temporal_basal_velocity_interval;
    public $active_temporal_basal_velocity_interval;
    public $active_temporal_basal_velocity_value;

    public $basal_values;
    public $current_tab;

    public $current_record_type_id;

    public function mount($current_tab=null)
    {
        $this->user = auth()->user();

        if (is_null($this->user->personalSettings->timezone_id)) {
            return redirect()->route('profile', ['current_tab' => 'timezone']);
        }

        $this->user_timezone_id = $this->user->personalSettings->timezone_id;
        $this->user_timezone_name = $this->user->personalSettings->timezone->timezone_name;
        $this->show_datetime_type = $this->user->personalSettings->show_datetime_type;

        if (isset($current_tab)) {
            $this->record = $this->user->records->find($current_tab);
            if (!isset($this->record)) {
                return redirect()->route('home');
            }
            $this->formatted_current_datetime = $this->UTCtoTimezoneLocal($this->record->datetime, $this->user_timezone_name);
        }

        $this->formatted_current_datetime = $this->formatted_current_datetime ?? $this->user->getDatetimeLocal();

        $this->carbonhydrate_types = CarbonhydrateType::select('id', 'name')->get();

        $this->physical_activity_types = PhysicalActivityType::select('id', 'name')->get();

        $this->stress_level_types = StressLevelType::select('id', 'name')->get();

        $this->current_record_type_id = $this->checkRecordType();

        $this->getRecord();
    }

    public function getRecord()
    {
        $validated = $this->validate(
            [
                'formatted_current_datetime' => 'required|date_format:Y-m-d\TH:i',
            ],
            [
                'formatted_current_datetime.date_format' => 'Время должно быть в формате ГГГГ-ММ-ДДTЧЧ:ММ',
                'formatted_current_datetime.required' => 'Время не может быть пустым',
            ]
        );

        $current_datetime = $this->timezoneLocalToUTCWithSeconds($this->formatted_current_datetime, $this->user_timezone_name);

        if ($this->current_record_type_id != 1) {
            $this->record = $this->user->getExperimentRecordByDatetime($current_datetime, $this->user->experiment_id);
        }
        else {
            $this->record = $this->user->getRecordByDatetime($current_datetime);
        }

        if (isset($this->record->id)) {
            $this->changeTab($this->record->id);
        }

        $this->basal_values = $this->record->basalValues;

        $this->sugar_level = $this->record->sugarLevel;

        $this->fast_carbonhydrates = $this->record->fastCarbonhydrate();
        $this->middle_carbonhydrates = $this->record->middleCarbonhydrate();
        $this->slow_carbonhydrates = $this->record->slowCarbonhydrate();

        $this->bolus = $this->record->bolusInjection();
        $this->prolonged_bolus = $this->record->prolongedInjection();

        $this->physical_activity_session = $this->record->physicalActivitySession;
        $this->active_physical_activity_session = $this->record->activePhysicalActivitySession();
        $this->next_physical_activity_session = $this->record->nextPhysicalActivitySession();

        $this->stress_level_session = $this->record->stressLevelSession;
        $this->active_stress_level_session = $this->record->activeStressLevelSession();
        $this->next_stress_level_session = $this->record->nextStressLevelSession();

        $this->sleeping_session = $this->record->sleepingSession;
        $this->active_sleeping_session = $this->record->activeSleepingSession();

        $this->desease_session = $this->record->deseaseSession;
        $this->active_desease_session = $this->record->activeDeseaseSession();

        $this->temporal_basal_velocity = $this->record->temporalBasalVelocity;
        $this->active_temporal_basal_velocity = $this->record->activeTemporalBasalVelocity();

        $this->cannula_changing_status = $this->record->cannulaChanging;

        $this->sugar_level_value = isset($this->sugar_level) ? number_format($this->sugar_level->val, 1) : null;

        $this->fast_carbonhydrates_value = isset($this->fast_carbonhydrates) ? number_format($this->fast_carbonhydrates->val, 1) : null;
        $this->middle_carbonhydrates_value = isset($this->middle_carbonhydrates) ? number_format($this->middle_carbonhydrates->val, 1) : null;
        $this->slow_carbonhydrates_value = isset($this->slow_carbonhydrates) ? number_format($this->slow_carbonhydrates->val, 1) : null;

        $this->bolus_value = isset($this->bolus) ? number_format($this->bolus->val, 1) : null;

        $this->prolonged_bolus_value = isset($this->prolonged_bolus) ? number_format($this->prolonged_bolus->val, 1) : null;
        $this->prolonged_bolus_interval = isset($this->prolonged_bolus) ? date('H:i', strtotime($this->prolonged_bolus->interval)) : null;

        $this->physical_activity_session_value = $this->physical_activity_session ?? $this->active_physical_activity_session;
        $this->physical_activity_session_value = isset($this->physical_activity_session_value) ? $this->physical_activity_session_value->physicalActivityType->id : 1;

        $this->stress_level_session_value = $this->stress_level_session ?? $this->active_stress_level_session;
        $this->stress_level_session_value = isset($this->stress_level_session_value) ? $this->stress_level_session_value->stressLevelType->id : 1;

        $this->sleeping_session_value = isset($this->sleeping_session) || isset($this->active_sleeping_session);
        $this->desease_session_value = isset($this->desease_session) || isset($this->active_desease_session);
        $this->cannula_changing_status_value = isset($this->cannula_changing_status);

        $this->temporal_basal_velocity_value = isset($this->temporal_basal_velocity) ? number_format($this->temporal_basal_velocity->val, 1) : null;
        $this->temporal_basal_velocity_interval = isset($this->temporal_basal_velocity) ? date('H:i', strtotime($this->temporal_basal_velocity->interval)) : null;
        $this->active_temporal_basal_velocity_interval = isset($this->active_temporal_basal_velocity) ? $this->active_temporal_basal_velocity->remains($current_datetime) : null;

        $this->active_temporal_basal_velocity_value = isset($this->active_temporal_basal_velocity) ? $this->active_temporal_basal_velocity->percentage : null;

        $this->preventRender = false;
    }

    public function setUrl() {
        $this->dispatch('setUrl', route('home', ['current_tab' => $this->current_tab]));
    }

    public function changeTab($tab)
    {
        $this->current_tab = $tab;
        $this->setUrl();
    }

    public function checkRecordType(){
        $experiment = $this->user->experiment;
        if (isset($experiment->id)) {
            if($experiment->id == $this->user->experiment_id){ 
                return 2;
            }
        }
        else{
            return 1;
        }
    }

    public function saveRecord()
    {
        $current_datetime = $this->timezoneLocalToUTCWithSeconds($this->formatted_current_datetime, $this->user_timezone_name);

        if ($this->current_record_type_id != 1) {
            $this->record = $this->user->getExperimentRecordByDatetime($current_datetime, $this->user->experiment_id);
        }
        else {
            $this->record = $this->user->getRecordByDatetime($current_datetime);
        }

        $this->validate(
            [
                'prolonged_bolus_interval' => 'nullable|date_format:H:i',
                'temporal_basal_velocity_interval' => 'nullable|date_format:H:i',
                'sugar_level_value' => 'nullable|decimal:0,1',
            ],
            [
                'prolonged_bolus_interval.date_format' => 'Время должно быть в формате ЧЧ:ММ',
                'temporal_basal_velocity_interval.date_format' => 'Время должно быть в формате ЧЧ:ММ',
                'sugar_level_value.decimal' => 'Значение должно быть числом с плавающей точкой до 1 знака после запятой',
            ]
        );
        if (is_null($this->record->id)) {
            $this->record->save();
        }

        if($this->record->record_type_id == 2){
            $isset_experiment = $this->record->experiments()->where('experiments.id', $this->user->experiment_id)->exists();
            if (!$isset_experiment) {
                $this->record->experiments()->attach($this->user->experiment_id); 
            }
        }

        $isset_sugar_level = isset($this->sugar_level);
        $isset_sugar_level_value = isset($this->sugar_level_value) && $this->sugar_level_value != '';
        if ($isset_sugar_level && $isset_sugar_level_value) {
            $this->sugar_level->update([
                'val' => $this->sugar_level_value
            ]);
        } elseif ($isset_sugar_level && !$isset_sugar_level_value) {
            $this->sugar_level->delete();
        } elseif (!$isset_sugar_level && $isset_sugar_level_value) {
            SugarLevel::create([
                'record_id' => $this->record->id,
                'sugar_level_type_id' => 2,
                'val' => $this->sugar_level_value
            ]);
        }

        $isset_fast_carbonhydrates = isset($this->fast_carbonhydrates);
        $isset_fast_carbonhydrates_value = isset($this->fast_carbonhydrates_value) && $this->fast_carbonhydrates_value != '';
        if ($isset_fast_carbonhydrates && $isset_fast_carbonhydrates_value) {
            $this->fast_carbonhydrates->update([
                'val' => $this->fast_carbonhydrates_value
            ]);
        } elseif ($isset_fast_carbonhydrates && !$isset_fast_carbonhydrates_value) {
            $this->fast_carbonhydrates->delete();
        } elseif (!$isset_fast_carbonhydrates && $isset_fast_carbonhydrates_value) {
            Carbonhydrate::create([
                'record_id' => $this->record->id,
                'carbonhydrate_type_id' => 1,
                'val' => $this->fast_carbonhydrates_value
            ]);
        }

        $isset_middle_carbonhydrates = isset($this->middle_carbonhydrates);
        $isset_middle_carbonhydrates_value = isset($this->middle_carbonhydrates_value) && $this->middle_carbonhydrates_value != '';
        if ($isset_middle_carbonhydrates && $isset_middle_carbonhydrates_value) {
            $this->middle_carbonhydrates->update([
                'val' => $this->middle_carbonhydrates_value
            ]);
        } elseif ($isset_middle_carbonhydrates && !$isset_middle_carbonhydrates_value) {
            $this->middle_carbonhydrates->delete();
        } elseif (!$isset_middle_carbonhydrates && $isset_middle_carbonhydrates_value) {
            Carbonhydrate::create([
                'record_id' => $this->record->id,
                'carbonhydrate_type_id' => 2,
                'val' => $this->middle_carbonhydrates_value
            ]);
        }

        $isset_slow_carbonhydrates = isset($this->slow_carbonhydrates);
        $isset_slow_carbonhydrates_value = isset($this->slow_carbonhydrates_value) && $this->slow_carbonhydrates_value != '';
        if ($isset_slow_carbonhydrates && $isset_slow_carbonhydrates_value) {
            $this->slow_carbonhydrates->update([
                'val' => $this->slow_carbonhydrates_value
            ]);
        } elseif ($isset_slow_carbonhydrates && !$isset_slow_carbonhydrates_value) {
            $this->slow_carbonhydrates->delete();
        } elseif (!$isset_slow_carbonhydrates && $isset_slow_carbonhydrates_value) {
            Carbonhydrate::create([
                'record_id' => $this->record->id,
                'carbonhydrate_type_id' => 3,
                'val' => $this->slow_carbonhydrates_value
            ]);
        }

        $isset_bolus = isset($this->bolus);
        $isset_bolus_value = isset($this->bolus_value) && $this->bolus_value != '';
        if ($isset_bolus && $isset_bolus_value) {
            $this->bolus->update([
                'val' => $this->bolus_value
            ]);
        } elseif ($isset_bolus && !$isset_bolus_value) {
            $this->bolus->delete();
        } elseif (!$isset_bolus && $isset_bolus_value) {
            InsulinInjection::create([
                'record_id' => $this->record->id,
                'val' => $this->bolus_value,
                'interval' => '00:00'
            ]);
        }

        $isset_prolonged_bolus = isset($this->prolonged_bolus);
        $isset_prolonged_bolus_value = isset($this->prolonged_bolus_value) && $this->prolonged_bolus_value != '';
        if ($isset_prolonged_bolus && $isset_prolonged_bolus_value) {
            $this->prolonged_bolus->update([
                'val' => $this->prolonged_bolus_value,
                'interval' => $this->prolonged_bolus_interval
            ]);
        } elseif ($isset_prolonged_bolus && !$isset_prolonged_bolus_value) {
            $this->prolonged_bolus->delete();
        } elseif (!$isset_prolonged_bolus && $isset_prolonged_bolus_value) {
            InsulinInjection::create([
                'record_id' => $this->record->id,
                'val' => $this->prolonged_bolus_value,
                'interval' => $this->prolonged_bolus_interval
            ]);
        }

        $isset_temporal_basal_velocity = isset($this->temporal_basal_velocity);
        $isset_temporal_basal_velocity_value = isset($this->temporal_basal_velocity_value) && $this->temporal_basal_velocity_value != '' && isset($this->temporal_basal_velocity_interval) && $this->temporal_basal_velocity_interval != '';
        $isset_active_temporal_basal_velocity = isset($this->active_temporal_basal_velocity);
        if ($isset_temporal_basal_velocity && $isset_temporal_basal_velocity_value) {
            $this->temporal_basal_velocity->update([
                'interval' => $this->temporal_basal_velocity_interval,
                'percentage' => $this->temporal_basal_velocity_value
            ]);
        } elseif ($isset_temporal_basal_velocity && !$isset_temporal_basal_velocity_value) {
            $this->temporal_basal_velocity->delete();
        } elseif (!$isset_temporal_basal_velocity && $isset_temporal_basal_velocity_value) {
            TemporalBasalVelocity::create([
                'record_id' => $this->record->id,
                'interval' => $this->temporal_basal_velocity_interval,
                'percentage' => $this->temporal_basal_velocity_value
            ]);
            if ($isset_active_temporal_basal_velocity) {
                $this_record_datetime = \Carbon\Carbon::parse($this->record->datetime);
                $this->active_temporal_basal_velocity = $this->record->activeTemporalBasalVelocity();
                $datetime_from_active_temporal_basal_velocity = \Carbon\Carbon::parse($this->active_temporal_basal_velocity->record->datetime);
                $difference = $datetime_from_active_temporal_basal_velocity->diff($this_record_datetime)->format('%H:%I');
                $this->active_temporal_basal_velocity->update([
                    'interval' => $difference
                ]);
            }
        }

        $isset_cannula_changing_status = isset($this->cannula_changing_status);
        $isset_cannula_changing_status_value = isset($this->cannula_changing_status_value) && $this->cannula_changing_status_value;
        if ($isset_cannula_changing_status && !$isset_cannula_changing_status_value) {
            $this->cannula_changing_status->delete();
        } elseif (!$isset_cannula_changing_status && $isset_cannula_changing_status_value) {
            CannulaChanging::create([
                'record_id' => $this->record->id,
            ]);
        }

        $isset_sleeping_session = isset($this->sleeping_session);
        $isset_active_sleeping_session = isset($this->active_sleeping_session);
        $isset_sleeping_session_value = isset($this->sleeping_session_value) && $this->sleeping_session_value;
        if (!$isset_sleeping_session && !$isset_active_sleeping_session && $isset_sleeping_session_value) {
            SleepingSession::create([
                'start_record' => $this->record->id,
            ]);
        } elseif ($isset_sleeping_session && !$isset_sleeping_session_value) {
            $this->sleeping_session->delete();
        } elseif ($isset_active_sleeping_session && !$isset_sleeping_session_value) {
            $this->active_sleeping_session = $this->record->activeSleepingSession();
            $this->active_sleeping_session->update([
                'end_record' => $this->record->id
            ]);
        }

        $isset_desease_session = isset($this->desease_session);
        $isset_active_desease_session = isset($this->active_desease_session);
        $isset_desease_session_value = isset($this->desease_session_value) && $this->desease_session_value;

        if (!$isset_desease_session && !$isset_active_desease_session && $isset_desease_session_value) {
            DeseaseSession::create([
                'start_record' => $this->record->id,
            ]);
        } elseif ($isset_desease_session && !$isset_desease_session_value) {
            $this->desease_session->delete();
        } elseif ($isset_active_desease_session && !$isset_desease_session_value) {
            $this->active_desease_session = $this->record->activeDeseaseSession();
            $this->active_desease_session->update([
                'end_record' => $this->record->id
            ]);
        }

        $this->physical_activity_session = $this->record->physicalActivitySession;
        $this->active_physical_activity_session = $this->record->activePhysicalActivitySession();
        $this->next_physical_activity_session = $this->record->nextPhysicalActivitySession();
        $isset_active_physical_activity_session = isset($this->active_physical_activity_session);
        $isset_physical_activity_session = isset($this->physical_activity_session);
        $isset_next_physical_activity_session = isset($this->next_physical_activity_session);
        if ($isset_physical_activity_session) {
            $this->physical_activity_session->update([
                'physical_activity_type_id' => $this->physical_activity_session_value,
            ]);
        } else {
            $this->physical_activity_session = PhysicalActivitySession::create([
                'start_record' => $this->record->id,
                'physical_activity_type_id' => $this->physical_activity_session_value,
            ]);
        }
        if ($isset_active_physical_activity_session) {
            if ($this->active_physical_activity_session->physicalActivityType->id == $this->physical_activity_session_value) {
                $this->physical_activity_session->delete();
                if ($isset_next_physical_activity_session) {
                    if ($this->active_physical_activity_session->physicalActivityType->id == $this->next_physical_activity_session->physicalActivityType->id) {
                        $this->next_physical_activity_session->delete();
                    }
                }
            }
        }

        $this->stress_level_session = $this->record->stressLevelSession;
        $this->active_stress_level_session = $this->record->activeStressLevelSession();
        $this->next_stress_level_session = $this->record->nextStressLevelSession();
        $isset_active_stress_level_session = isset($this->active_stress_level_session);
        $isset_stress_level_session = isset($this->stress_level_session);
        $isset_next_stress_level_session = isset($this->next_stress_level_session);
        if ($isset_stress_level_session) {
            $this->stress_level_session->update([
                'stress_level_type_id' => $this->stress_level_session_value,
            ]);
        } else {
            $this->stress_level_session = StressLevelSession::create([
                'start_record' => $this->record->id,
                'stress_level_type_id' => $this->stress_level_session_value,
            ]);
        }
        if ($isset_active_stress_level_session) {
            if ($this->active_stress_level_session->stressLevelType->id == $this->stress_level_session_value) {
                $this->stress_level_session->delete();
                if ($isset_next_stress_level_session) {
                    if ($this->active_stress_level_session->stressLevelType->id == $this->next_stress_level_session->stressLevelType->id) {
                        $this->next_stress_level_session->delete();
                    }
                }
            }
        }

        if ($this->record->record_type_id != 1) {
            return $this->redirect(route('experiments', ['current_tab' => $this->user->experiment_id]));
        }

        $date = Carbon::Parse($this->formatted_current_datetime)->format('Y-m-d');
        return $this->redirect(route('records', ['current_tab' => $date]));
    }

    public function actualizeChartData() {
        $current_datetime = $this->timezoneLocalToUTCWithSeconds($this->formatted_current_datetime, $this->user_timezone_name);
        $sugar_levels = $this->user->getNearSugarLevels($current_datetime);
        if (count($sugar_levels) > 0) {
            $this->dispatch('updateChart', 0, $sugar_levels);
        }
        $cgm_records = $this->user->getNearCGMSugarLevels($current_datetime);
        if (count($cgm_records) > 0) {
            $this->dispatch('updateChart', 1, $cgm_records);
        }
    }

    public function render()
    {
        return view('livewire.home');
    }
}