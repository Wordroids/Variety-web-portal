<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("passwords", function (Blueprint $table) {
            $table->id();
            $table->foreignId("event_id")->constrained()->cascadeOnDelete();
            $table->foreignId("role_id")->constrained()->cascadeOnDelete();
            $table->string("password");
            $table->timestamps();

            $table->unique(["role_id", "event_id"]); // One password per event
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("passwords");
    }
};
