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
        Schema::create('souscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            $table->unsignedBigInteger('offre_id');
            $table->foreign('offre_id')->references('id')->on('offres');
            $table->string('nom_offre');
            $table->string('nom_client');
            $table->integer('prix_total');
            $table->string('adresse_actuelle');
            $table->string('nouvvelle_adresse');
            $table->string('description');
            $table->dateTime('date_demenagement');
            $table->enum('statut', ['Actif', 'Inactif'])->default('Actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('souscriptions');
    }
};
