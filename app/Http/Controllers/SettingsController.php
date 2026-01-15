<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show()
    {
        $settings = Settings::firstOrCreate();
        return view("pages.settings.index", compact("settings"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "location_tracking_api" => "nullable|string|max:255",
        ]);

        $settings = Settings::firstOrCreate();
        $settings->update($validated);

        return back()->with("success", "Settings updated successfully.");
    }
}
