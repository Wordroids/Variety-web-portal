<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::with('events', 'roles', 'users')->paginate();
        $events = Event::all();
        $roles = Role::all();
        $users = User::all();
        return view('pages.notifications.index', compact('notifications', 'events', 'roles', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'string',
            'target_type' => 'required|string|in:event,role,user',
            'target_events' => 'array|required_if:target_type,event',
            'target_events.*' => 'nullable|exists:events,id',
            'target_roles' => 'array|required_if:target_type,role',
            'target_roles.*' => 'nullable|exists:roles,id',
            'target_users' => 'array|required_if:target_type,user',
            'target_users.*' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,scheduled,sent',
            'schedule_date' => 'date',
            'schedule_time' => 'date_format:H:i',
        ]);

        $notification = DB::transaction(function () use ($validated) {
            $scheduledAt = $validated['status'] === 'scheduled'
                ? Carbon::createFromFormat('Y-m-d H:i', $validated['schedule_date'] . ' ' . $validated['schedule_time'])
                : null;

            $notification = Notification::create([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'target_type' => $validated['target_type'],
                'status' => $validated['status'],
                'scheduled_at' => $scheduledAt,
            ]);

            switch ($validated['target_type']) {
                case 'event':
                    $notification->events()->sync($validated['target_events'] ?? []);
                    break;
                case 'role':
                    $notification->roles()->sync($validated['target_roles'] ?? []);
                    break;
                case 'user':
                    $notification->users()->sync($validated['target_users'] ?? []);
                    break;
                default:
                    throw ValidationException::withMessages([
                        'target_type' => ['Selected target type is invalid.'],
                    ]);
            }

            return $notification;
        });

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
