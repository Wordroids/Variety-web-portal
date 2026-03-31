<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EventFormController;
use App\Http\Controllers\API\EventPermitController;
use App\Http\Controllers\API\LocationEndpointController;
use App\Http\Controllers\API\MedicalRecordCommentController;
use App\Http\Controllers\API\MedicalRecordController;
use App\Http\Controllers\API\MedicalRecordImageController;
use App\Http\Controllers\API\NotificationController;
use App\Models\MedicalRecordImage;
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

// Private routes
Route::middleware("auth:sanctum")->group(function () {
    // Events
    Route::get("/events", [EventController::class, "index"])->name(
        "api.events.index",
    );

    // Forms
    Route::get("/events/{event}/forms", [
        EventFormController::class,
        "index",
    ])->name("api.events.forms.index");

    // Permits
    Route::get("/events/{event}/permits", [
        EventPermitController::class,
        "index",
    ])->name("api.events.permits.index");

    Route::post("/events/{event}/permits", [
        EventPermitController::class,
        "store",
    ])->name("api.events.permits.store");

    Route::delete("/events/{event}/permits/{permit}", [
        EventPermitController::class,
        "destroy",
    ])->name("api.events.permits.destory");

    // Medical Records
    Route::get("/events/{event}/medical", [
        MedicalRecordController::class,
        "index",
    ])->name("api.events.medical.index");

    // Comments
    Route::get("/events/{event}/medical/{record}/comments", [
        MedicalRecordCommentController::class,
        "index",
    ])->name("api.events.medical.comments.index");

    Route::post("/events/{event}/medical/{record}/comments", [
        MedicalRecordCommentController::class,
        "store",
    ])->name("api.events.medical.comments.store");

    Route::delete("/events/{event}/medical/{record}/comments/{comment}", [
        MedicalRecordCommentController::class,
        "destroy",
    ])->name("api.events.medical.comments.destroy");

    // Images
    Route::get("/events/{event}/medical/{record}/images", [
        MedicalRecordImageController::class,
        "index",
    ])->name("api.events.medical.images.index");

    Route::post("/events/{event}/medical/{record}/images", [
        MedicalRecordImageController::class,
        "store",
    ])->name("api.events.medical.images.store");

    Route::delete("/events/{event}/medical/{record}/images/{image}", [
        MedicalRecordImageController::class,
        "destroy",
    ])->name("api.events.medical.images.destroy");

    // Location
    Route::get("/location-endpoint", [
        LocationEndpointController::class,
        "index",
    ])->name("api.location-endpoint");

    // Notifications
    Route::get("/notifications", [
        NotificationController::class,
        "index",
    ])->name("api.notifications");

    Route::post("/notifications/token", [
        NotificationController::class,
        "token",
    ])->name("api.notifications.set_token");
});
