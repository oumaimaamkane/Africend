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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname', 30);
            $table->string('lastname', 30);
            $table->string('email', 50)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('address', 50)->nullable();
            $table->string('password')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_rib')->nullable();
            $table->integer('role_id');
            $table->timestamp('email_verified_at')->nullable(); 
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
