<?php

namespace App\Livewire\Data;

use Livewire\Component;
use App\Models\BasalValue;
use App\Models\Period;

use Carbon\Carbon;
use App\Traits\DatetimeTrait;

class Basal extends Component
{
    use DatetimeTrait;

    protected $listeners = ['reRenderBasalList' => 'render'];

    public $user;
    public $current_changing;
    public $current_changing_id;

    public $formatted_current_datetime;
    public $formatted_basal_values;

    public $periods;
    public $user_timezone_id;
    public $user_timezone_name;
    public $show_datetime_type;
    public function mount($id=null)
    {
        $this->user = auth()->user();

        if (is_null($this->user->personalSettings->timezone_id)) {
            return redirect()->route('profile', ['current_tab' => 'timezone']);
        }

        $this->user_timezone_id = $this->user->personalSettings->timezone_id;
        $this->user_timezone_name = $this->user->personalSettings->timezone->timezone_name;
        $this->show_datetime_type = $this->user->personalSettings->show_datetime_type;

        $this->current_changing_id = $id;
        $this->periods = Period::all();

        if (isset($this->current_changing_id)) {
            $this->changeBasal($this->current_changing_id);
        }
        $this->setUrl();
    }
    public function setUrl() {
        $this->dispatch('setUrl', route('basal', ['id' => $this->current_changing_id]));
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

    public function changeBasal($current_changing_id='create') {
        $this->current_changing_id = $current_changing_id;
        
        if ($this->current_changing_id == 'create') {
            $this->current_changing = 'create';
            $this->formatted_basal_values = [];
            foreach($this->periods as $period) {
                $this->formatted_basal_values[] = number_format(0.5, 2, '.', '');
            }
            $this->formatted_current_datetime = $this->user->getDatetimeLocal();
        } else {
            $this->current_changing = $this->user->basalChangings->find($current_changing_id);
            if (!isset($this->current_changing)) {
                return redirect()->route('basal');
            }

            $this->formatted_basal_values = [];
            foreach($this->current_changing->basalValues as $basal_value) {
                $this->formatted_basal_values[] = $basal_value->val;
            }
            $this->formatted_current_datetime = $this->UTCtoTimezone($this->current_changing->datetime, $this->user_timezone_name);
        }

        $this->setUrl();
    }

    public function loadBasal() {
        $current_datetime = $this->timezoneLocalToUTC($this->formatted_current_datetime, $this->user_timezone_name);
        $copy_values_from = $this->user->basalChangingsAtDatetime($current_datetime);

        if (isset($copy_values_from)) {
            foreach($copy_values_from->basalValues as $basal_value) {
                $this->formatted_basal_values[$basal_value->period->period - 1] = $basal_value->val;
            }
        }
    }
    public function saveBasal() {
        $current_datetime = $this->timezoneLocalToUTCWithSeconds($this->formatted_current_datetime, $this->user_timezone_name);

        $this->current_changing = $this->user->getRecordByDatetime($current_datetime);

        if (is_null($this->current_changing->id)) {
            $this->current_changing->record_type_id = $this->checkRecordType();
            
            $this->current_changing->save();
        }
        if($this->current_changing->record_type_id == 2){
            $this->current_changing->experiments()->attach($this->user->experiment_id); 
        }

        $this->current_changing->datetime = $current_datetime;
        $this->current_changing->user_id = $this->user->id;
        $this->current_changing->timezone_id = $this->user_timezone_id;
        $this->current_changing->save();

        $this->current_changing->basalValues()->delete();
        foreach($this->periods as $period) {
            $basal_value = new BasalValue();
            $basal_value->val = $this->formatted_basal_values[$period->period - 1];
            $basal_value->record_id = $this->current_changing->id;
            $basal_value->period_id = $period->id;
            $basal_value->save();
        }
        $this->current_changing_id = $this->current_changing->id;

        $this->setUrl();
    }
    public function render()
    {
        return view('livewire.data.basal');
    }
}
