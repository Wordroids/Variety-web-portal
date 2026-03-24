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
        Schema::create("medical_record_items", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("collection_id")
                ->references("id")
                ->on("medical_record_collections")
                ->cascadeOnDelete();
            $table
                ->foreignId("participant_id")
                ->nullable()
                ->unique()
                ->references("id")
                ->on("event_participants")
                ->cascadeOnDelete();
            $table->longText("content");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("medical_record_items");
    }
};
