<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            "success" => true,
            "notifications" => auth()->user()->notifications(),
        ]);
    }
}
