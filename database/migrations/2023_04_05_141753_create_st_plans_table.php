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
        if (!Schema::hasTable('st_plans')) {
            Schema::create('st_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 100)->unique('name')->comment('A unique key for plan, eg: gold, diamond ..');
                $table->integer('is_default')->default(0);
                $table->integer('trial_period')->default(0);
                $table->string('trial_interval', 20)->default('day');
                $table->integer('order')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_plans')->insert([
                [
                    'id' => 1,
                    'slug' => 'basic',
                    'is_default' => 1,
                    'trial_period' => 0,
                    'trial_interval' => 'day',
                    'order' => 0,
                    'created_at' => '2023-03-21 22:36:25',
                    'updated_at' => '2023-03-21 22:36:25',
                ],
                [
                    'id' => 2,
                    'slug' => 'premium',
                    'is_default' => 0,
                    'trial_period' => 7,
                    'trial_interval' => 'day',
                    'order' => 7,
                    'created_at' => '2023-03-21 22:37:48',
                    'updated_at' => '2023-03-21 22:37:48',
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
        Schema::dropIfExists('st_plans');
    }
};
