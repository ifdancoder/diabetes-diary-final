<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalSettings extends Model
{
    use HasFactory;

    protected $table = 'personal_settings';
    protected $fillable = [
        'user_id',
        'log_in_out_notifications',
        'reminder_notifications',
        'notifications_from_social',
        'timezone_id',
        'show_datetime_type',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function timezone() {
        return $this->belongsTo(Timezone::class);
    }
}
