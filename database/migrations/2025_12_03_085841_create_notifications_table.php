<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message')->nullable();
            $table->enum('target_type', ['event', 'role', 'user']);
            $table->foreignId('event_id')->nullable()->constrained();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->enum('status', ['draft', 'scheduled', 'sent'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
