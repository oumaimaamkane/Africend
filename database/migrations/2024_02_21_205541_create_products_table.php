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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->unsignedBigInteger('country_id')->nullable()->default(null);
            $table->string('title');
            $table->text('description');
            $table->decimal('price', $precision = 8, $scale = 2);
            $table->json('image');
            $table->integer('initial_quantity');
            $table->set('status' , ['In hold' , 'Picked up' , 'Shipped' , 'In warehouse' ])->default('In hold');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null'); 
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
