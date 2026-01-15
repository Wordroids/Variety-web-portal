<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Notification $notification)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if notification is already sent
        if ($this->notification->status === "sent") {
            return;
        }

        // Find all participants
        $participants = $this->notification->participants();

        // Get all expo push tokens
        $tokens = $participants->pluck("push_token");

        // Send notification to all participants with one request

        // Update notification status
        // $this->notification->update([
        //     "status" => "sent",
        //     "sent_at" => now(),
        // ]);
    }
}
