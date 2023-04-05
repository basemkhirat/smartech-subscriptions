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
        if (!Schema::hasTable('st_currencies_translations')) {
            Schema::create('st_currencies_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('currency_id');
                $table->string('lang', 3);
                $table->string('name');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_currencies_translations')->insert([
                [
                    'id' => 1,
                    'currency_id' => 1,
                    'lang' => 'en',
                    'name' => 'Dollars',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 2,
                    'currency_id' => 1,
                    'lang' => 'ar',
                    'name' => 'دولار',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
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
        Schema::dropIfExists('st_currencies_translations');
    }
};
