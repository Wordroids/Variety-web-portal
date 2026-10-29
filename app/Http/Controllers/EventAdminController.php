<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    use AuthorizesRequests;

    public function index(Event $event)
    {
        $this->authorize("viewAny", [EventAdmin::class, $event]);

        $users = User::all();
        $admins = $event->admins()->paginate(10);

        return view(
            "pages.events.admins.index",
            compact("event", "admins", "users"),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize("create", [EventAdmin::class, $event]);

        $validated = $request->validate([
            "user_id" => "required|exists:users,id",
        ]);

        $event->admins()->syncWithoutDetaching([$validated["user_id"]]);

        return back()->with("success", "Admin added.");
    }

    public function destroy(Event $event, User $admin)
    {
        $this->authorize("delete", [EventAdmin::class, $event]);

        $event->admins()->detach($admin->id);

        return back()->with("success", "Admin removed.");
    }
}
