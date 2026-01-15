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
        Schema::create("notification_role", function (Blueprint $table) {
            $table->id();
            $table->foreignId("role_id")->constrained()->cascadeOnDelete();
            $table
                ->foreignId("notification_id")
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(["role_id", "notification_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("notification_role");
    }
};
