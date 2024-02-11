<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('quote_priceline', 10)->after('quote_id');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id');
            $table->string('statut', 20);
            $table->string('num_souscription', 15);
            $table->string('ref_souscription', 15);
            $table->date('date_souscription');
            $table->string('url');
            $table->string('cgv');
            $table->text('codes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('quote_priceline');
        });

        Schema::dropIfExists('reservations');
    }
}
