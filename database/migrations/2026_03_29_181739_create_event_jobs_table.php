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
        Schema::create("event_jobs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("event_id");

            // Event Day
            $table->integer("event_day");

            // Vehicle & Codes
            $table->string("vehicle");
            $table->string("duty_code");
            $table->text("duty_description");

            // Location details
            $table->string("location");
            $table->enum("period", ["AM", "PM"]);

            // Numbers & Times
            $table->decimal("km", 8, 2)->default(0);
            $table->time("ov_arrive")->nullable();
            $table->time("field_arrive")->nullable();
            $table->time("ov_departure")->nullable();

            // Additional Info
            $table->text("comment")->nullable();
            $table->string("image_path")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("event_jobs");
    }
};
