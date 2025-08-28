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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->index();
            $table->enum("status", ["open", "closed", "pending_agent", "agent_assigned"])->index();
            $table->boolean("is_first_contact")->default(1);
            $table->boolean("is_automated")->default(1);
            $table->integer("agent_id")->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
