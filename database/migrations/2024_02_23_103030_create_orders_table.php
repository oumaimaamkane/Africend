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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable()->default(null);
            $table->string('name');
            $table->string('number');
            $table->string('city');
            $table->text('address');
            $table->integer('quantity');
            $table->set('status' , ['Pas encore confirmé','Confirmé','Appel en cours','En cours','Rejeté','Annulé','Reporté','Retour','Pas de réponse','Doublon','en attente de livraison','Livré'])->default('Pas encore confirmé');
            $table->integer('tentative')->nullable();
            $table->decimal('price', $precision = 8, $scale = 2);
            $table->unsignedBigInteger('confirmed_by')->nullable()->default(null);
            $table->unsignedBigInteger('assigned_to')->nullable()->default(null);
            $table->unsignedBigInteger('delivery_id')->nullable()->default(null);
            $table->set('in_bl' , ['Y' ,'N'])->nullable()->default('N');
            $table->text('comment')->nullable()->default(null);
            $table->dateTime('postponed_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null'); 
            $table->foreign('confirmed_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null'); 
            $table->foreign('assigned_to')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null'); 
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
