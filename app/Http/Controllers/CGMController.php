<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\DatetimeTrait;
use App\Models\SugarLevel;

use Carbon\Carbon;

class CGMController extends Controller
{
    use DatetimeTrait;
    public function createCGMrecord(Request $request) {
        $relation = 0.0556;

        $user = auth()->user();
        $timezone_name = $user->personalSettings->timezone->name;
        $request_data = json_encode($request->all());
        
        foreach($request->all() as $key => $value) {
            if (is_numeric($key)) {
                $timestamp_with_offset = $value['date'];
                $timestamp = substr($timestamp_with_offset, 0, -3);
                $datetime = Carbon::createFromTimestamp($timestamp)->format('Y-m-d H:i');

                $current_datetime = $this->timezoneToUTCWithSeconds($datetime, $timezone_name);
                $sgv = $value['sgv'] * $relation;
                
                $record_in_datetime = $user->getRecordByDatetime($current_datetime);
                $record_in_datetime->save();

                $sugar_model = $record_in_datetime->sugarLevelCGM;

                $isset_sugar_level = isset($sugar_model);
                $isset_sugar_level_value = isset($sgv);

                if ($isset_sugar_level && $isset_sugar_level_value) {
                    $sugar_model->update([
                        'val' => $sgv
                    ]);
                } elseif (!$isset_sugar_level && $isset_sugar_level_value) {
                    SugarLevel::create([
                        'record_id' => $record_in_datetime->id,
                        'sugar_level_type_id' => 1,
                        'val' => $sgv
                    ]);
                }
            }
        }
    }
}
