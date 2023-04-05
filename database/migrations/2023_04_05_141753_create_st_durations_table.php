<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('st_durations')) {
            Schema::create('st_durations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 50)->unique('slug')->comment('A unique name for duration, eg: month, year ..');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_durations')->insert([
                [
                    'id' => 1,
                    'slug' => 'hour',
                    'created_at' => '2023-03-21 22:39:32',
                    'updated_at' => '2023-03-21 22:39:32',
                ],
                [
                    'id' => 2,
                    'slug' => 'day',
                    'created_at' => '2023-03-21 22:39:32',
                    'updated_at' => '2023-03-21 22:39:32',
                ],
                [
                    'id' => 3,
                    'slug' => 'week',
                    'created_at' => '2023-03-21 22:39:32',
                    'updated_at' => '2023-03-21 22:39:32',
                ],
                [
                    'id' => 4,
                    'slug' => 'month',
                    'created_at' => '2023-03-21 22:39:32',
                    'updated_at' => '2023-03-21 22:39:32',
                ],
                [
                    'id' => 5,
                    'slug' => 'year',
                    'created_at' => '2023-03-21 22:39:32',
                    'updated_at' => '2023-03-21 22:39:32',
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('st_durations');
    }
};
