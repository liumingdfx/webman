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
       $this->schema()->create('users', function (Blueprint $table) {
            $table->id();
           $table->string('name');
           $table->string('email')->unique();
           $table->timestamp('email_verified_at')->nullable();
           $table->string('password');
           $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->schema()->dropIfExists('users');
    }
};
