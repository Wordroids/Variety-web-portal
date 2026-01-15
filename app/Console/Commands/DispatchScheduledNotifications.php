<?php

namespace App\Console\Commands;

use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Console\Command;

class DispatchScheduledNotifications extends Command
{
    protected $signature = "notifications:dispatch";
    protected $description = "Dispatch scheduled notifications";

    public function handle()
    {
        Notification::query()
            ->where("status", "scheduled")
            ->where("scheduled_at", "<=", now())
            ->chunkById(100, function ($notifications) {
                foreach ($notifications as $notification) {
                    SendNotificationJob::dispatch($notification);
                }
            });
    }
}
