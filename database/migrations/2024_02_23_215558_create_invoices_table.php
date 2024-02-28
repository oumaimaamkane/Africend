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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->json('orders_ids');
            $table->integer('nbr_orders');
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->decimal('amount_net', $precision = 8, $scale = 2);
            $table->set('status' , ['Non Payé' , 'Payé'])->default('Non Payé');
            $table->timestamps();

            // foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
