<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriersAtAirportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers_at_airport', function (Blueprint $table) {
            $table->string('airport_code');
            $table->string('carrier_code');
            $table->timestamps();
        });

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            DB::table('carriers_at_airport')->insert(
                [
                    'airport_code' => $file_row['airport']['code'],
                    'carrier_code' => $file_row['carrier']['code'],
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
        Schema::dropIfExists('carriers_at_airport');
    }
}
