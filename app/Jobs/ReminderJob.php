<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Mail\NoticeMail;
use App\Notifications\ReminderNotification;
use App\Reminder;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

/**
 * Class ReminderJob
 *
 * @package App\Jobs
 */
class ReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $reminderData = Reminder::all()->toArray();

        foreach ($reminderData as $data) {
            if (Carbon::parse($data['reminder_date'])->isToday()) {
                Mail::to($data['email'])->send(new NoticeMail($data));

                Notification::send(
                    User::query()->find($data['user_id']),
                    new ReminderNotification($data)
                );

                Reminder::query()->find($data['id'])->delete();
            }
        }
    }
}
