<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('airport_code');
            $table->string('carrier_code');
            $table->integer('month');
            $table->integer('year');

            $table->unique(['airport_code', 'carrier_code', 'month', 'year']);

            $table->timestamps();
        });

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            DB::table('statistics')->insert(
                [
                    'airport_code' => $file_row['airport']['code'],
                    'carrier_code' => $file_row['carrier']['code'],
                    'month' => $file_row['time']['month'],
                    'year' => $file_row['time']['year'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}
