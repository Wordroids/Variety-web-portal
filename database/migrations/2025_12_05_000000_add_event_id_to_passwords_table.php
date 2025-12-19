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
        Schema::table("passwords", function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(["role_id"]);

            // Then drop the unique constraint from role_id since we'll have multiple passwords per role (one per event)
            $table->dropUnique(["role_id"]);

            // Recreate the foreign key constraint without the unique constraint
            $table
                ->foreign("role_id")
                ->references("id")
                ->on("roles")
                ->onDelete("cascade");

            // Add event_id column
            $table
                ->foreignId("event_id")
                ->nullable()
                ->after("id")
                ->constrained()
                ->cascadeOnDelete();

            // Make role_id + event_id combination unique
            $table->unique(["role_id", "event_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("passwords", function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(["role_id", "event_id"]);

            // Drop the event_id column
            $table->dropForeign(["event_id"]);
            $table->dropColumn("event_id");

            // Drop the foreign key constraint on role_id
            $table->dropForeign(["role_id"]);

            // Restore the original unique constraint on role_id
            $table->unique("role_id");

            // Recreate the foreign key constraint with unique constraint
            $table
                ->foreign("role_id")
                ->references("id")
                ->on("roles")
                ->onDelete("cascade");
        });
    }
};
