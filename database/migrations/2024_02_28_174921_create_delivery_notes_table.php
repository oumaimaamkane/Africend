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
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('reference');
            $table->set('type' , ['BL' , 'BR']);
            $table->json('orders_tn');
            $table->integer('nbr_orders');
            $table->string('pickup_city');
            $table->string('pickup_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
