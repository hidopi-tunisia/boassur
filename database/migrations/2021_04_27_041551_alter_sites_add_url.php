<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSitesAddUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('url');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->string('quote_nom', 80)->after('quote_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('url');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('quote_nom');
        });
    }
}
