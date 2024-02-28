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
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client');
            $table->string('nom_entreprise');
            $table->dateTime('delai');
            $table->dateTime('date_demenagement');
            $table->string('adresse_actuelle');
            $table->string('nouvelle_adresse');
            $table->string('description');
            $table->integer('prix_total');
            $table->string('commentaire')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            $table->unsignedBigInteger('demenageur_id');
            $table->foreign('demenageur_id')->references('id')->on('users');
            $table->enum('statut', ['Actif', 'Inactif'])->default('Actif');
            $table->enum('etat', ['En Cours', 'Termine', 'Annule'])->default('En Cours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestations');
    }
};
