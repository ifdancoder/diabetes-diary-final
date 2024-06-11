<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Record;

use Carbon\Carbon;
use Log;

class RecordReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $remind_interval_minutes = 500;

        $users = User::all();
        foreach ($users as $user) {
            Log::info('User: ' . $user->id);
            $last_user_record = $user->userRecords()->first();
            if ($last_user_record) {
                Log::info('Last record: ' . $last_user_record->created_at . ' for user: ' . $user->id);
                $last_record_datetime = Carbon::parse($last_user_record->created_at);
                $now = Carbon::now();
                Log::info('Now: ' . $now . '. Last record datetime: ' . $last_record_datetime . '. Diff: ' . $now->diffInMinutes($last_record_datetime) . ' minutes');
                $diff = $now->diffInMinutes($last_record_datetime);
                if ($diff > $remind_interval_minutes) {
                    $user->notify(new \App\Notifications\RecordReminder());
                }
            }
        }
    }
}
