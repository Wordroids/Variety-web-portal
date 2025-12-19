<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventParticipantAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::prefix("auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"])->name("api.login");
    Route::post("/participant-login", [
        EventParticipantAuthController::class,
        "login",
    ])->name("api.participant.login");

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"])->name(
            "api.logout",
        );
        Route::get("/profile", [AuthController::class, "profile"])->name(
            "api.profile",
        );
    });
});
