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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default("0");
            $table->set('type' , ["Changement d adresse","Annulation de commande","Remboursement","Changement de prix","Facturation","Autres"])->default(null);
            $table->unsignedBigInteger('country_id');
            $table->string('city');
            $table->string('message');
            $table->set('status' , [ "Non traité encore", "En cours de traitement","Traité"])->default('Non traité encore');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
