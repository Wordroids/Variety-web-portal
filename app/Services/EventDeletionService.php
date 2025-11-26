<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class EventDeletionService
{
    public function delete(Event $event): void
    {
        $event->load(['days.locations', 'days.resources']);

        foreach ($event->days as $day) {
            // Remove images
            if ($day->image_path && Storage::disk('public')->exists($day->image_path)) {
                Storage::disk('public')->delete($day->image_path);
            }

            $day->locations()->delete();
            $day->resources()->delete();
        }

        $event->days()->delete();
        $event->delete();
    }
}
