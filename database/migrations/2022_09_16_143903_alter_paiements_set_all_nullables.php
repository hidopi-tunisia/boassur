<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paiements', function (Blueprint $table) {
            // $table->string('PM', 30)->nullable()->change();
            // $table->string('ACCEPTANCE', 30)->nullable()->change();
            // $table->string('STATUS', 2)->nullable()->change();
            // $table->string('CARDNO', 20)->nullable()->change();
            // $table->string('ED', 10)->nullable()->change();
            // $table->string('CN', 100)->nullable()->change();
            // $table->string('TRXDATE', 8)->nullable()->change();
            // $table->string('PAYID', 16)->nullable()->change();
            // $table->string('PAYIDSUB', 3)->nullable()->change();
            // $table->string('NCERROR', 3)->nullable()->change();
            // $table->string('BRAND', 20)->nullable()->change();
            // $table->string('IPCTY', 2)->nullable()->change();
            // $table->string('CCCTY', 2)->nullable()->change();
            // $table->string('ECI')->nullable()->change();
            // $table->string('CVCCheck', 3)->nullable()->change();
            // $table->string('AAVCheck', 3)->nullable()->change();
            // $table->string('VC', 10)->nullable()->change();
            // $table->ipAddress('IP')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
