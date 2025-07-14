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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable();
            $table->timestamp('pkg_start_time')->nullable();
            $table->timestamp('pkg_end_time')->nullable();
            $table->integer('package_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['package_id', 'pkg_start_time', 'pkg_end_time','package_status']);
        });
    }
};
