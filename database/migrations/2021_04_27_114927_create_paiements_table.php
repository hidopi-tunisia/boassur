<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id');
            $table->string('currency', 3);
            $table->string('amount', 10);
            $table->string('PM', 30);
            $table->string('ACCEPTANCE', 30);
            $table->string('STATUS', 2);
            $table->string('CARDNO', 20);
            $table->string('ED', 10);
            $table->string('CN', 100);
            $table->string('TRXDATE', 8);
            $table->string('PAYID', 16);
            $table->string('PAYIDSUB', 3);
            $table->string('NCERROR', 3);
            $table->string('BRAND', 20);
            $table->string('IPCTY', 2);
            $table->string('CCCTY', 2);
            $table->string('ECI');
            $table->string('CVCCheck', 3);
            $table->string('AAVCheck', 3);
            $table->string('VC', 10);
            $table->ipAddress('IP');
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
        Schema::dropIfExists('paiements');
    }
}
