<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinutesDelayedStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minutes_delayed_statistics', function (Blueprint $table) {
            $table->increments('statistics_id');
            $table->integer('late_aircraft');
            $table->integer('weather');
            $table->integer('carrier');
            $table->integer('security');
            $table->integer('total');
            $table->integer('national_aviation_system');
            $table->timestamps();


//            $table->foreign('statistics_id')->references('id')->on('statistics');
        });

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            DB::table('minutes_delayed_statistics')->insert(
                [
                    'late_aircraft' => $file_row['statistics']['minutes delayed']['late aircraft'],
                    'weather' => $file_row['statistics']['minutes delayed']['weather'],
                    'carrier' => $file_row['statistics']['minutes delayed']['carrier'],
                    'security' => $file_row['statistics']['minutes delayed']['security'],
                    'total' => $file_row['statistics']['minutes delayed']['total'],
                    'national_aviation_system' => $file_row['statistics']['minutes delayed']['national aviation system'],
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
        Schema::dropIfExists('minutes_delayed_statistics');
    }
}
