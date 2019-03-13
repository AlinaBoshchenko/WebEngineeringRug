<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumOfDelaysStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('num_of_delays_statistics', function (Blueprint $table) {
            $table->increments('statistics_id');
            $table->integer('late_aircraft');
            $table->integer('weather');
            $table->integer('security');
            $table->integer('national_aviation_system');
            $table->integer('carrier');
            $table->timestamps();

//            $table->foreign('statistics_id')->references('id')->on('statistics');
        });

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            DB::table('num_of_delays_statistics')->insert(
                [
                    'late_aircraft' => $file_row['statistics']['# of delays']['late aircraft'],
                    'weather' => $file_row['statistics']['# of delays']['weather'],
                    'security' => $file_row['statistics']['# of delays']['security'],
                    'national_aviation_system' => $file_row['statistics']['# of delays']['national aviation system'],
                    'carrier' => $file_row['statistics']['# of delays']['carrier'],
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
        Schema::dropIfExists('num_of_delays_statistics');
    }
}
