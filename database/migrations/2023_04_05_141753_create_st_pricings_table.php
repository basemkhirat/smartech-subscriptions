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
        if (!Schema::hasTable('st_pricings')) {
            Schema::create('st_pricings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('plan_id');
                $table->double('amount')->default(0);
                $table->string('currency', 10)->default('usd');
                $table->integer('invoice_period')->nullable()->default(1);
                $table->string('invoice_interval', 20)->nullable()->default('month');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_pricings')->insert([
                [
                    'id' => 1,
                    'plan_id' => 1,
                    'amount' => 0.0,
                    'currency' => 'usd',
                    'invoice_period' => 1,
                    'invoice_interval' => 'month',
                    'created_at' => '2023-03-21 23:18:52',
                    'updated_at' => '2023-03-21 23:18:52',
                ],
                [
                    'id' => 2,
                    'plan_id' => 2,
                    'amount' => 10.0,
                    'currency' => 'usd',
                    'invoice_period' => 1,
                    'invoice_interval' => 'month',
                    'created_at' => '2023-03-21 23:18:52',
                    'updated_at' => '2023-03-21 23:18:52',
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
        Schema::dropIfExists('st_pricings');
    }
};
