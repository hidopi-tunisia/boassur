<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCommandesAddSendinblue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('email_id')->nullable();
            $table->string('email_statut', 60)->nullable();
            $table->dateTime('email_date')->nullable();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->string('attestation')->nullable();
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
            $table->dropColumn(['email_id', 'email_statut', 'email_date']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['attestation']);
        });
    }
}
