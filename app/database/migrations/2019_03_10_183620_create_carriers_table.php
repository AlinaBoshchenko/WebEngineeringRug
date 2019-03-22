<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->string('carrier_code')->unique();
            $table->string('carrier_name');
            $table->timestamps();
        }
        );

        ini_set('memory_limit', '400M');

        $file = file_get_contents(__DIR__ . "/../../resources/airlines.json");
        $file_rows = json_decode($file, true);

        foreach ($file_rows as $file_row) {
            $carrier_code = DB::table('carriers')->where('carrier_code', '=', $file_row['carrier']['code'])->first();

            if ($carrier_code == null) {
                DB::table('carriers')->updateOrInsert(
                    [
                        'carrier_code' => $file_row['carrier']['code'],
                        'carrier_name' => $file_row['carrier']['name'],
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
        Schema::dropIfExists('carriers');
    }
}
