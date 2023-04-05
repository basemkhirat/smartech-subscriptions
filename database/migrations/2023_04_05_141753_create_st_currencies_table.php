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
        if (!Schema::hasTable('st_currencies')) {
            Schema::create('st_currencies', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 100)->unique('slug')->comment('A unique name for feature, eg: access_specific_section, ad_free ..');
                $table->string('symbol', 10)->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_currencies')->insert([
                'id' => 1,
                'slug' => 'usd',
                'symbol' => '$',
                'created_at' => '2023-03-21 22:39:32',
                'updated_at' => '2023-03-21 22:39:32',
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
        Schema::dropIfExists('st_currencies');
    }
};
