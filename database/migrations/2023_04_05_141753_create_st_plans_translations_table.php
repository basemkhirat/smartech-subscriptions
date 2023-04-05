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
        if (!Schema::hasTable('st_plans_translations')) {
            Schema::create('st_plans_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('plan_id');
                $table->string('lang', 3);
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_plans_translations')->insert([
                [
                    'id' => 1,
                    'plan_id' => 1,
                    'lang' => 'en',
                    'name' => 'Basic Plan',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:36:57',
                    'updated_at' => '2023-03-21 22:36:57',
                ],
                [
                    'id' => 2,
                    'plan_id' => 1,
                    'lang' => 'ar',
                    'name' => 'الباقة الأساسية',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:36:57',
                    'updated_at' => '2023-03-21 22:36:57',
                ],
                [
                    'id' => 3,
                    'plan_id' => 2,
                    'lang' => 'en',
                    'name' => 'Premium Plan',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:36:57',
                    'updated_at' => '2023-03-21 22:36:57',
                ],
                [
                    'id' => 4,
                    'plan_id' => 2,
                    'lang' => 'ar',
                    'name' => 'الباقة المدفوعة',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:36:57',
                    'updated_at' => '2023-03-21 22:36:57',
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
        Schema::dropIfExists('st_plans_translations');
    }
};
