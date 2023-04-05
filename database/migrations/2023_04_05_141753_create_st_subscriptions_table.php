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
        if (!Schema::hasTable('st_subscriptions')) {
            Schema::create('st_subscriptions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->index('user_id');
                $table->integer('plan_id')->index('plan_id');
                $table->integer('pricing_id')->nullable()->default(0)->index('pricing_id');
                $table->double('amount')->nullable()->default(0)->index('amount');
                $table->string('currency', 10)->nullable()->default('usd');
                $table->integer('is_trial')->nullable()->default(0)->index('is_trial');
                $table->integer('is_active')->nullable()->default(1)->index('is_active');
                $table->timestamp('created_at')->useCurrent()->index('created_at');
                $table->timestamp('updated_at')->useCurrent()->index('updated_at');
                $table->dateTime('started_at')->nullable()->index('started_at');
                $table->dateTime('ended_at')->nullable()->index('ended_at');
                $table->dateTime('canceled_at')->nullable();
            });

            DB::table('st_subscriptions')->insert([
                [
                    'id' => 38,
                    'user_id' => 7080,
                    'plan_id' => 2,
                    'pricing_id' => 2,
                    'amount' => 10.0,
                    'currency' => 'usd',
                    'is_trial' => 0,
                    'is_active' => 1,
                    'created_at' => '2023-04-03 22:45:21',
                    'updated_at' => '2023-04-05 12:18:23',
                    'started_at' => '2023-04-05 12:16:52',
                    'ended_at' => '2023-05-05 12:16:52',
                    'canceled_at' => '2023-04-05 12:18:23',
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
        Schema::dropIfExists('st_subscriptions');
    }
};
