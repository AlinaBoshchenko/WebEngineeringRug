<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_statistics', function (Blueprint $table) {
            $table->increments('statistics_id');
            $table->integer('cancelled');
            $table->integer('on_time');
            $table->integer('total');
            $table->integer('delayed');
            $table->integer('diverted');
            $table->timestamps();

            $table->foreign('statistics_id')->references('id')->on('statistics');
        });

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            DB::table('flight_statistics')->insert(
                [
                    'cancelled' => $file_row['statistics']['flights']['cancelled'],
                    'on_time' => $file_row['statistics']['flights']['on time'],
                    'total' => $file_row['statistics']['flights']['total'],
                    'delayed' => $file_row['statistics']['flights']['delayed'],
                    'diverted' => $file_row['statistics']['flights']['diverted'],
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
        Schema::dropIfExists('flight_statistics');
    }
}
