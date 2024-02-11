<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Création de la table des Sites
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 60);
            $table->boolean('actif')->default(0);
            $table->timestamps();
        });

        // Création de la table des Destinations
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->char('alpha2', 2);
            $table->char('alpha3', 3);
            $table->char('code', 3);
            $table->string('nom', 50);
            $table->char('article', 10)->nullable();
            $table->timestamps();
        });

        // Création de la table des commandes
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->unsignedInteger('site_id')->index();
            $table->string('quote_id', 20)->comment('ID search quote');
            $table->date('depart');
            $table->date('retour');
            $table->unsignedInteger('prix_voyage');
            $table->unsignedInteger('montant');
            $table->timestamps();
        });

        // Création de la table des voyageurs
        Schema::create('voyageurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->string('civilite');
            $table->string('nom', 60);
            $table->string('prenom', 60);
            $table->string('email');
            $table->string('telephone', 20);
            $table->date('date_naissance');
            $table->string('cp', 5);
            $table->string('ville', 40);
            $table->string('adresse', 120);
            $table->string('adresse2', 120)->nullable();
        });

        Schema::create('accompagnants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->string('nom', 60);
            $table->string('prenom', 60);
            $table->date('date_naissance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
        Schema::dropIfExists('destinations');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('voyageurs');
        Schema::dropIfExists('accompagnants');
    }
}
