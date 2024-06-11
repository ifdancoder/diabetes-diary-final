<?php

namespace App\Livewire\Data;

use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

use App\Models\SugarLevel;

class CGM extends Component
{
    use WithFileUploads;

    protected $listeners = ['actualizeChartData' => 'actualizeChartData', 'pointClick' => 'pointClick'];

    public $user, $current_tab, $newAttachment, $chartData, $data, $is_changing_bounds, $is_chart_changed, $temp_data, $flip_flop, $left_bound, $right_bound;

    public function mount($current_tab = null)
    {
        $this->user = auth()->user();
        if (isset($current_tab)) {
            $this->changeTab($current_tab);
        }
        $this->is_changing_bounds = false;
        $this->flip_flop = false;
        $this->is_chart_changed = false;
    }

    public function setUrl()
    {
        $this->dispatch('setUrl', route('cgm', ['current_tab' => $this->current_tab]));
    }

    public function changeTab($tab)
    {
        $this->current_tab = $tab;
        if ($this->current_tab != 'create') {
            $this->newAttachment = $this->user->getMedia('cgm')->find($this->current_tab);
        }
        $this->setUrl();
    }

    public function addAttachment()
    {
        $this->validate([
            'newAttachment' => 'required|file|max:1048576|mimes:xml',
        ], [
            'newAttachment.required' => 'Файл не выбран',
            'newAttachment.max' => 'Файл слишком большой',
            'newAttachment.mimes' => 'Файл должен быть XML',
        ]);
        $xmlReader = new \XMLReader();
        $xmlReader->open($this->newAttachment->getRealPath());
        $parsedData = [];
        while ($xmlReader->read()) {
            if ($xmlReader->nodeType == \XMLReader::ELEMENT && $xmlReader->name == 'Record' && $xmlReader->getAttribute('type') == 'HKQuantityTypeIdentifierBloodGlucose') {
                $startDate = $xmlReader->getAttribute('startDate');
                $value = $xmlReader->getAttribute('value');
                $parsedData[] = [
                    'datetime' => $startDate,
                    'sugar_level' => (float) $value,
                ];
            }
        }
        usort($parsedData, function ($a, $b) {
            return strtotime($a['datetime']) - strtotime($b['datetime']);
        });
        $jsonData = json_encode($parsedData);
        $fileName = 'med_data_' . uniqid() . '.json';
        $this->user->addMediaFromString($jsonData)
            ->usingFileName($fileName)
            ->toMediaCollection('cgm');
    }

    public function actualizeChartData()
    {
        $file_path = $this->newAttachment->getPath();
        $jsonData = file_get_contents($file_path);
        $this->data = json_decode($jsonData, true);

        foreach ($this->data as $key => $value) {
            $dateTime = Carbon::createFromFormat('Y-m-d H:i:s O', $this->data[$key]['datetime']);
            $this->data[$key]['datetime'] = $dateTime->format('Y-m-d H:i:s');
            $this->data[$key]['val'] = $this->data[$key]['sugar_level'];
            unset($this->data[$key]['sugar_level']);
        }

        $this->updChart();
    }

    public function updChart()
    {
        $this->dispatch('updateChart', 0, $this->data);
    }

    public function changeBounds()
    {
        $this->is_changing_bounds = !$this->is_changing_bounds;
    }

    public function resetBounds() {
        $this->is_changing_bounds = false;
        $this->left_bound = null;
        $this->right_bound = null;
        $this->is_chart_changed = false;
        $this->updChart();
    }

    public function pointClick($point)
    {
        if ($this->is_changing_bounds) {
            $this->flip_flop = !$this->flip_flop;
            if ($this->flip_flop) {
                if ($point != 0) {
                    $this->js('deleteAllLeft(' . $point . ')');
                    $this->is_chart_changed = true;
                    $this->left_bound = $point;
                }
            } else {
                if ($point != 0) {
                    $this->js('deleteAllRight(' . $point . ')');
                    $this->right_bound = $point;
                }
                $this->is_changing_bounds = false;
            }
        }
    }

    public function saveSugarLevels()
    {
        $left_bound = $this->left_bound ?? 0;
        $right_bound = $this->right_bound ?? count($this->data) - 1;

        for($index = $left_bound; $index <= $right_bound; $index++) {
            $sugar_level_value = $this->data[$index]['val'];

            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $this->data[$index]['datetime'], $this->user->personalSettings->timezone->timezone_name)->setTimezone('UTC')->format('Y-m-d H:i:00');

            $record = $this->user->getRecordByDatetime($datetime);
            $record->save();

            $record_cgm = $record->sugarLevelCGM;
            $isset_sugar_level = isset($record_cgm);
            if ($isset_sugar_level) {
                $record_cgm->update([
                    'val' => $sugar_level_value
                ]);
            } elseif (!$isset_sugar_level) {
                SugarLevel::create([
                    'record_id' => $record->id,
                    'sugar_level_type_id' => 1,
                    'val' => $sugar_level_value
                ]);
            }
        }

        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.data.c-g-m');
    }
}
