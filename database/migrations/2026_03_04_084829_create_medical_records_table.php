<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('vehicle');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nickname')->nullable();

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('address4')->nullable();
            $table->string('address5')->nullable();
            $table->string('address6')->nullable();

            $table->string('mobile')->nullable();

            $table->string('next_of_kin')->nullable();
            $table->string('nok_phone')->nullable();
            $table->string('nok_alt_phone')->nullable();

            $table->date('dob')->nullable();

            $table->text('allergies')->nullable();
            $table->text('dietary_requirement')->nullable();
            $table->text('past_medical_history')->nullable();
            $table->text('current_medical_history')->nullable();
            $table->text('current_medications')->nullable();

            $table->string('vehicle_image')->nullable();
            $table->json('images')->nullable();

            $table->text('comments')->nullable();

            $table->date('destroy_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
