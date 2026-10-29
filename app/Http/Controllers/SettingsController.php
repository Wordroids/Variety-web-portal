<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use AuthorizesRequests;

    public function show()
    {
        $this->authorize("viewAny", Settings::class);

        $settings = Settings::firstOrCreate();
        return view("pages.settings.index", compact("settings"));
    }

    public function store(Request $request)
    {
        $settings = Settings::firstOrCreate();

        $this->authorize("update", $settings);

        $validated = $request->validate([
            "location_tracking_api" => "nullable|string|max:255",
        ]);

        $settings->update($validated);

        return back()->with("success", "Settings updated successfully.");
    }
}
