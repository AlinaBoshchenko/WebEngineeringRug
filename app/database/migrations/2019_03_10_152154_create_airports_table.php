<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->string('airport_code')->unique();
            $table->string('airport_name');
            $table->timestamps();
        });

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            $airport_code = DB::table('airports')->where('airport_code', '=', $file_row['airport']['code'])->first();

            if ($airport_code == null) {
                DB::table('airports')->updateOrInsert(
                    [
                        'airport_code' => $file_row['airport']['code'],
                        'airport_name' => $file_row['airport']['name'],
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
}
