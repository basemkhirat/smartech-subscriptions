<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('st_trials')) {
            Schema::create('st_trials', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->index('user_id');
                $table->integer('plan_id')->index('plan_id');
                $table->timestamp('created_at')->useCurrent()->index('created_at');
                $table->timestamp('updated_at')->useCurrent()->index('updated_at');
                $table->dateTime('started_at')->nullable()->index('started_at');
                $table->dateTime('ended_at')->nullable()->index('ended_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('st_trials');
    }
};
