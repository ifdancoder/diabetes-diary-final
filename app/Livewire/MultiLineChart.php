<?php

namespace App\Livewire;

use Livewire\Component;

class MultiLineChart extends Component
{
    protected $listeners = ['updateChart'];
    
    public $chart_num, $chart_names, $chart_colors, $chart_values, $datasets_string, $height, $width;
    public function mount($num, $names, $colors, $values = null, $height = 32, $width = 90)
    {
        $this->chart_num = $num;
        $this->chart_names = $names;
        $this->chart_colors = $colors;
        $this->chart_values = $values;
        $this->height = $height;
        $this->width = $width;
    }
    public function initLines() {
        for($i = 0; $i < $this->chart_num; $i++) {
            $this->js('addLineIntoChart(chart, \'' . $this->chart_names[$i] . '\', \'' . $this->chart_colors[$i] . '\')');
            if (isset($this->chart_values)) {
                $this->js('updateLineChart(chart, ' . json_encode($this->chart_values[$i]) . ', ' . $i . ')');
            }
        }
    }
    public function updateChart($chart_index, $new_values)
    {
        $this->js('updateLineChart(chart, ' . json_encode($new_values) . ', ' . $chart_index . ')');
    }

    public function actualizeChart() {
        $this->dispatch('actualizeChartData');
    }

    public function render()
    {
        return view('livewire.multi-line-chart');
    }
}
