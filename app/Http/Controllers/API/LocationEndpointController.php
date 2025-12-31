<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Settings;

class LocationEndpointController extends Controller
{
    public function index()
    {
        $settings = Settings::first();
        return response()->json(
            [
                "message" => $settings->location_tracking_api
                    ? "Location tracking endpoint shown."
                    : "Location tracking endpoint not found.",
                "endpoint" => $settings->location_tracking_api ?? null,
            ],
            $settings->location_tracking_api ? 200 : 404,
        );
    }
}
