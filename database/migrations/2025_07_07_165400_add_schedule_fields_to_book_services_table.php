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
        Schema::table('book_services', function (Blueprint $table) {
            $table->boolean('schedule_created')->default(false)->after('status');
            $table->string('schedule_pdf')->nullable()->after('schedule_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_services', function (Blueprint $table) {
            $table->dropColumn(['schedule_created', 'schedule_pdf']);
        });
    }
};
