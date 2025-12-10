<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('notification',[
            'title' => 'nullable|string',
            'message' => 'string',
            'target_type' => 'required|string|in:event,role,user',
            'target_events' => 'array|required_if:target_type,event',
            'target_events.*' => 'nullable|exists:events,id',
            'target_roles' => 'array|required_if:target_type,role',
            'target_roles.*' => 'nullable|exists:roles,id',
            'target_users' => 'array|required_if:target_type,user',
            'target_users.*' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,scheduled,sent',
            'schedule_date' => 'nullable|required_if:status,scheduled|date',
            'schedule_time' => 'nullable|required_if:status,scheduled|date_format:H:i',
        ]);

        $notification = DB::transaction(function () use ($validated) {
            $scheduledAt = $validated['status'] === 'scheduled'
                ? Carbon::createFromFormat('Y-m-d H:i', $validated['schedule_date'] . ' ' . $validated['schedule_time'])
                : null;

            $notification = Notification::create([
                'title' => $validated['title'] ?? 'Untitled',
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validateWithBag('notification',[
            'title' => 'nullable|string',
            'message' => 'string',
            'target_type' => 'required|string|in:event,role,user',
            'target_events' => 'array|required_if:target_type,event',
            'target_events.*' => 'nullable|exists:events,id',
            'target_roles' => 'array|required_if:target_type,role',
            'target_roles.*' => 'nullable|exists:roles,id',
            'target_users' => 'array|required_if:target_type,user',
            'target_users.*' => 'nullable|exists:users,id',
            'status' => 'required|in:draft,scheduled,sent',
            'schedule_date' => 'nullable|required_if:status,scheduled|date',
            'schedule_time' => 'nullable|required_if:status,scheduled|date_format:H:i',
        ]);

        DB::transaction(function () use ($notification, $validated) {
            $scheduledAt = $validated['status'] === 'scheduled'
                ? Carbon::createFromFormat('Y-m-d H:i', $validated['schedule_date'] . ' ' . $validated['schedule_time'])
                : null;

            $notification->update([
                'title' => $validated['title'] ?? 'Untitled',
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
        });

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }


    public function import(Request $request)
    {
        $request->validateWithBag('import',[
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        $header = array_map('trim', array_shift($rows));

        $errors = new MessageBag();
        $validRows = [];
        $importedCount = 0;

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because CSV header is row 1

            $data = array_combine($header, $row);

            // Convert target fields from CSV
            $data['target_events'] = !empty($data['target_events']) ? array_filter(explode(',', $data['target_events'])) : [];
            $data['target_roles'] = !empty($data['target_roles']) ? array_filter(explode(',', $data['target_roles'])) : [];
            $data['target_users'] = !empty($data['target_users']) ? array_filter(explode(',', $data['target_users'])) : [];

            // validate row
            $validator = validator($data, [
                'title' => 'nullable|string',
                'message' => 'required|string',
                'target_type' => 'required|in:event,role,user',

                'target_events' => 'array|required_if:target_type,event',
                'target_events.*' => 'nullable|exists:events,id',

                'target_roles' => 'array|required_if:target_type,role',
                'target_roles.*' => 'nullable|exists:roles,id',

                'target_users' => 'array|required_if:target_type,user',
                'target_users.*' => 'nullable|exists:users,id',

                'status' => 'required|in:draft,scheduled,sent',
                'schedule_date' => 'nullable|required_if:status,scheduled|date',
                'schedule_time' => 'nullable|required_if:status,scheduled|date_format:H:i',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $msg) {
                    $errors->add("row_" . $rowNumber - 1, "Row " . $rowNumber - 1 . ": $msg");
                }
                continue;
            }

            $validRows[] = $validator->validated();
        }

        if ($errors->isNotEmpty()) {
            return back()
                ->withErrors($errors, 'import')
                ->withInput();
        }

        foreach ($validRows as $validated) {
            DB::transaction(function () use ($validated) {
                $scheduledAt = $validated['status'] === 'scheduled'
                    ? Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $validated['schedule_date'] . ' ' . $validated['schedule_time']
                    )
                    : null;

                $notification = Notification::create([
                    'title' => $validated['title'] ?? 'Untitled',
                    'message' => $validated['message'],
                    'target_type' => $validated['target_type'],
                    'status' => $validated['status'],
                    'scheduled_at' => $scheduledAt,
                ]);

                switch ($validated['target_type']) {
                    case 'event':
                        $notification->events()->sync($validated['target_events']);
                        break;
                    case 'role':
                        $notification->roles()->sync($validated['target_roles']);
                        break;
                    case 'user':
                        $notification->users()->sync($validated['target_users']);
                        break;
                }
            });

            $importedCount++;
        }

        return redirect()
            ->route('notifications.index')
            ->with('success', "$importedCount notification(s) imported successfully.")
            ->with('import_errors', $errors->isNotEmpty() ? $errors : null);
    }
}
