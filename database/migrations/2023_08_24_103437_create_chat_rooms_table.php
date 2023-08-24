<?php

use Illuminate\Database\Schema\Blueprint;
use Eloquent\Migrations\Migrations\Migration;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       $this->schema()->create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('房间名');
            $table->unsignedInteger('master_id')->comment('房主id');
            $table->string('user_ids')->comment('房间用户id列表');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('chat_rooms');
    }
};
