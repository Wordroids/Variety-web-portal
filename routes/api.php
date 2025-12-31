<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\LocationEndpointController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes
Route::prefix("admin/auth")->group(function () {
    Route::post("/login", [AdminAuthController::class, "login"])->name(
        "api.admin.login",
    );

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/logout", [AdminAuthController::class, "logout"])->name(
            "api.admin.logout",
        );
        Route::get("/profile", [AdminAuthController::class, "profile"])->name(
            "api.admin.profile",
        );
    });
});

// Participant Authentication Routes
Route::prefix("auth")->group(function () {
    Route::post("/login", [AuthController::class, "login"])->name(
        "api.participant.login",
    );

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/logout", [AuthController::class, "logout"])->name(
            "api.participant.logout",
        );
        Route::get("/profile", [AuthController::class, "profile"])->name(
            "api.participant.profile",
        );
    });
});

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/events", [EventController::class, "index"])->name(
        "api.events.index",
    );

    Route::get("/location-endpoint", [
        LocationEndpointController::class,
        "index",
    ])->name("api.location-endpoint");
});
