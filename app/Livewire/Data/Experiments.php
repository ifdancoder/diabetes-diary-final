<?php

namespace App\Livewire\Data;

use App\Models\Experiment;
use App\Traits\DatetimeTrait;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Storage;

use Livewire\Component;
use Carbon\Carbon;

use App\Models\SugarLevel;

class Experiments extends Component
{
    protected $listeners = ['actualizeChartData' => 'actualizeChartData'];

    use DatetimeTrait;
    public $user;
    public $current_tab;
    public $current_experiment;
    public $formatted_current_datetime;
    public function mount($current_tab = null)
    {
        $this->user = auth()->user();
        if (isset($current_tab)) {
            $this->changeTab($current_tab);
        }
    }

    public function setUrl()
    {
        $this->dispatch('setUrl', route('experiments', ['current_tab' => $this->current_tab]));
    }

    public function createExperiment()
    {
        $new_experiment = Experiment::create(['user_id' => $this->user->id]);
        $this->user->experiment_id = $new_experiment->id;
        $this->current_experiment = $new_experiment;
        $this->changeTab($new_experiment->id);
        $this->user->save();
    }

    public function stopExperiment()
    {
        $this->user->experiment_id = null;
        $this->user->save();

        $current_datetime = $this->timezoneLocalToUTCWithSeconds($this->formatted_current_datetime, $this->user->personalSettings->timezone->timezone_name);
        $this->current_experiment->datetime = $current_datetime;
        $this->current_experiment->save();
    }

    public function stopExperimentPredict()
    {
        $current_datetime = $this->timezoneLocalToUTCWithSeconds($this->formatted_current_datetime, $this->user->personalSettings->timezone->timezone_name);
        $this->current_experiment->datetime = $current_datetime;
        $this->current_experiment->save();

        $minutes_in_dataset = 512;
        $hours_after = 6;

        $insulin_interval = 4;
        $insulin_interval_num = 60 / $insulin_interval;

        $this->stopExperiment();

        $prediction_datetime = Carbon::parse($this->user->getNearestSugarLevelCGMDatetimeByDatetime($current_datetime));
        $last_datetime = $prediction_datetime->copy()->addHours($hours_after);
        $first_datetime = $prediction_datetime->copy()->subMinutes($minutes_in_dataset);
        $first_cgm_datetime = Carbon::parse($this->user->getNearestSugarLevelCGMDatetimeByDatetime($prediction_datetime->copy()->subMinutes($minutes_in_dataset)));

        $active_basal_values = $this->user->activeBasalValuesByDatetime($first_cgm_datetime);

        $first_cgm_datetime_start_of_hour = $first_cgm_datetime->copy()->startOfHour()->minute(0)->second(0);

        $current = $first_cgm_datetime_start_of_hour->copy();

        $dataset = [];

        $current_physical_activity_intensity = 0;

        $current_stress_level = 0;

        $current_sleeping_status = 0;

        $current_desease_status = 0;

        $current_cannula_changing_time = 0;

        while ($current->lte($last_datetime)) {

            $current_record = $this->user->experimentUserRecordByDatetime($this->current_experiment->id, $current);

            $current_insulin = 0;

            $current_sugar_level = null;

            $current_fast_chs = 0;
            $current_middle_chs = 0;
            $current_slow_chs = 0;


            if ($current_record) {
                if ($current_record->basalValues->count() > 0) {
                    $active_basal_values = $current_record->basalValues;
                }
                if ($current_record->bolusInjection()) {
                    $current_insulin += $current_record->bolusInjection()->val;
                }
                if ($current_record->sugarLevelCGM) {
                    $current_sugar_level = $current_record->sugarLevelCGM->val;
                }
                if ($current_record->physicalActivitySession) {
                    $current_physical_activity_intensity = $current_record->physicalActivitySession->intensity;
                }
                if ($current_record->stressLevel) {
                    $current_stress_level = $current_record->stressLevel->intensity;
                }
                if ($current_record->fastCarbonhydrate()) {
                    $current_fast_chs = $current_record->fastCarbonhydrate()->val;
                }
                if ($current_record->middleCarbonhydrate()) {
                    $current_middle_chs = $current_record->middleCarbonhydrate()->val;
                }
                if ($current_record->slowCarbonhydrate()) {
                    $current_slow_chs = $current_record->slowCarbonhydrate()->val;
                }
                if ($current_record->cannulaChanging) {
                    $current_cannula_changing_time = 0;
                }
            }

            $current_datetime_in_record_timezone = isset($current_record) ? $current->copy()->setTimezone($current_record->timezone->timezone_name) : $current->copy()->setTimezone($this->user->personalSettings->timezone->timezone_name);

            $hour = $current_datetime_in_record_timezone->hour;
            $minute = $current_datetime_in_record_timezone->minute;

            if ($minute % $insulin_interval == 0) {
                $current_insulin += $active_basal_values[$hour]->val / $insulin_interval_num;
            }

            $current_step = [
                'datetime' => $current_datetime_in_record_timezone->format('Y-m-d H:i'),
                'fast_chs' => $current_fast_chs,
                'middle_chs' => $current_middle_chs,
                'long_chs' => $current_slow_chs,
                'insulin' => $current_insulin,
                'desease_status' => $current_desease_status,
                'sleeping_status' => $current_sleeping_status,
                'physical_activity_intensity' => $current_physical_activity_intensity,
                'stress_level' => 3,
                'cannula_changing_status' => $current_cannula_changing_time,
                'minutes' => $minute + 60 * $hour,
                'sugar_level' => $current_sugar_level,
            ];
            $current_cannula_changing_time += 1;
            $dataset[] = $current_step;
            $current->addMinute();
        }
        $dataset = json_encode(['data' => $dataset, 'datetimes' => ['first_cgm' => $first_cgm_datetime->copy()->setTimezone($this->user->personalSettings->timezone->timezone_name)->format('Y-m-d H:i'), 'start' => $first_datetime->copy()->setTimezone($this->user->personalSettings->timezone->timezone_name)->format('Y-m-d H:i'), 'predict' => $prediction_datetime->copy()->setTimezone($this->user->personalSettings->timezone->timezone_name)->format('Y-m-d H:i')]]);

        #we need to save that json to disk named experiments

        $file = fopen('/var/www/new-diabetes-diary.ru/app/Python/experiments/' . $this->current_experiment->id . '.json', 'w');
        fwrite($file, $dataset);
        fclose($file);

        $process = new Process(['python3', '/var/www/new-diabetes-diary.ru/app/Python/dataset_getter.py', $this->user->id, $this->current_experiment->id, 1]);

        $process->mustRun();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output_array = json_decode($process->getOutput());

        $first_inserting_datetime = $prediction_datetime->copy()->addMinute();
        foreach ($output_array as $key => $value) {
            $val = $value;

            $record = $this->user->getPredictedRecordByDatetime($first_inserting_datetime, $this->current_experiment->id);
            $record->save();

            $record->experiments()->attach($this->current_experiment->id);

            $record_cgm = $record->sugarLevelCGM;
            $isset_sugar_level = isset($record_cgm);
            if ($isset_sugar_level) {
                $record_cgm->update([
                    'val' => $val
                ]);
            } elseif (!$isset_sugar_level) {
                SugarLevel::create([
                    'record_id' => $record->id,
                    'sugar_level_type_id' => 3,
                    'val' => $val
                ]);
            }

            $first_inserting_datetime->addMinute();
        }
    }

    public function changeTab($tab)
    {
        $this->current_tab = $tab;
        $this->current_experiment = $this->user->experiments->find($tab);
        if ($this->current_experiment) {
            $this->formatted_current_datetime = $this->UTCtoTimezoneLocal($this->current_experiment->created_at, $this->user->personalSettings->timezone->timezone_name);
        }
        $this->dispatch('updateChart', 0, []);
        $this->dispatch('updateChart', 1, []);
        $this->actualizeChartData();
        $this->setUrl();
    }
    public function actualizeChartData()
    {
        if (isset($this->current_experiment) && isset($this->current_experiment->datetime)) {
            $cgm_records = $this->user->getNearCGMSugarLevels($this->current_experiment->datetime);
            if (count($cgm_records) > 0) {
                $this->dispatch('updateChart', 0, $cgm_records);
            }
            $predicted_records = $this->user->getNearPredictedSugarLevels($this->current_experiment->datetime, $this->current_experiment->id);
            if (count($predicted_records) > 0) {
                $this->dispatch('updateChart', 1, $predicted_records);
            }
        }
    }

    public function render()
    {
        return view('livewire.data.experiments');
    }
}
