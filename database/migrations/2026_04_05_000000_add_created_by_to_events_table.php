<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $eventIds = DB::table('events')->whereNull('created_by')->pluck('id');
        foreach ($eventIds as $eventId) {
            $row = DB::table('event_admin')
                ->where('event_id', $eventId)
                ->orderBy('created_at')
                ->first();
            if ($row) {
                DB::table('events')->where('id', $eventId)->update(['created_by' => $row->user_id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
