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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demande_devis_id');
            $table->foreign('demande_devis_id')->references('id')->on('demande_devis');
            $table->unsignedBigInteger('demenageur_id');
            $table->foreign('demenageur_id')->references('id')->on('users');
            $table->string('nom_client');
            $table->dateTime('date_demenagement');
            $table->integer('prix_total');
            $table->string('adresse_actuelle');
            $table->string('nouvelle_adresse');
            $table->string('description');
            $table->enum('statut', ['Actif', 'Inactif'])->default('Actif');
            $table->enum('action', ['En cours', 'Valide', 'Refuse'])->default('En cours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
