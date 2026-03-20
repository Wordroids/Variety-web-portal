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
        Schema::create("medical_record_collections", function (
            Blueprint $table,
        ) {
            $table->id();
            $table
                ->foreignId("event_id")
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->date("imported_at");
            $table->date("expires_at")->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("medical_record_collections");
    }
};
