<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeCsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commande_cses', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20);
            $table->text('contenu');
            $table->timestamps();
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->string('reference', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande_cses');

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('reference');
        });
    }
}
