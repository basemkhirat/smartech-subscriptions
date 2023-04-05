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
        if (!Schema::hasTable('st_plans_features')) {
            Schema::create('st_plans_features', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('plan_id');
                $table->integer('feature_id');
                $table->integer('order')->default(0);
            });

            DB::table('st_plans_features')->insert([
                [
                    'id' => 1,
                    'plan_id' => 1,
                    'feature_id' => 1,
                    'order' => 0,
                ],
                [
                    'id' => 2,
                    'plan_id' => 2,
                    'feature_id' => 1,
                    'order' => 0,
                ],
                [
                    'id' => 3,
                    'plan_id' => 2,
                    'feature_id' => 2,
                    'order' => 1,
                ],
                [
                    'id' => 4,
                    'plan_id' => 2,
                    'feature_id' => 3,
                    'order' => 2,
                ],
                [
                    'id' => 5,
                    'plan_id' => 2,
                    'feature_id' => 4,
                    'order' => 3,
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
        Schema::dropIfExists('st_plans_features');
    }
};
