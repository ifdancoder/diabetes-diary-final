<?php

namespace App\Traits;

use Carbon\Carbon;

trait DatetimeTrait
{
    public function UTCtoTimezone($string_datetime, $timezone_name) {
        return Carbon::parse($string_datetime)->setTimezone($timezone_name)->format('Y-m-d H:i');
    }
    public function timezoneToUTC($string_datetime, $timezone_name) {
        return Carbon::createFromFormat('Y-m-d H:i', $string_datetime, $timezone_name)->setTimezone('UTC')->format('Y-m-d H:i');
    }
    public function UTCtoTimezoneWithSeconds($string_datetime, $timezone_name) {
        return Carbon::parse($string_datetime)->setTimezone($timezone_name)->format('Y-m-d H:i:s');
    }
    public function timezoneToUTCWithSeconds($string_datetime, $timezone_name) {
        return Carbon::createFromFormat('Y-m-d H:i', $string_datetime, $timezone_name)->setTimezone('UTC')->format('Y-m-d H:i:s');
    }
    public function timezoneLocalToUTC($string_datetime, $timezone_name) {
        return Carbon::createFromFormat('Y-m-d\TH:i', $string_datetime, $timezone_name)->setTimezone('UTC')->format('Y-m-d H:i');
    }
    public function timezoneLocalToUTCWithSeconds($string_datetime, $timezone_name) {
        return Carbon::createFromFormat('Y-m-d\TH:i', $string_datetime, $timezone_name)->setTimezone('UTC')->format('Y-m-d H:i:s');
    }
    public function UTCtoTimezoneLocal($string_datetime, $timezone_name) {
        return Carbon::parse($string_datetime)->setTimezone($timezone_name)->format('Y-m-d\TH:i');
    }
}