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
            $table->string('deposit_url')->nullable()->after('status');
            $table->integer('deposit_status')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_services', function (Blueprint $table) {
            $table->dropColumn('deposit_url');
            $table->dropColumn('deposit_status');
        });
    }
};
