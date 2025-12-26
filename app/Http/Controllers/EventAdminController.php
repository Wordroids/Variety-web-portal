<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
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
        $validated = $request->validate([
            "user_id" => "required|exists:users,id",
        ]);

        $event->admins()->syncWithoutDetaching($validated["user_id"]);
        return back()->with("success", "Admin added.");
    }

    public function destroy(Request $request, Event $event, User $admin)
    {
        $event->admins()->detach($admin);
        return back()->with("success", "Admin removed.");
    }
}
