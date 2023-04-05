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
        if (!Schema::hasTable('st_durations_translations')) {
            Schema::create('st_durations_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('duration_id');
                $table->string('lang', 3);
                $table->string('name');
                $table->string('plural_name');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_durations_translations')->insert([
                [
                    'id' => 1,
                    'duration_id' => 1,
                    'lang' => 'en',
                    'name' => 'hour',
                    'plural_name' => 'hours',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 2,
                    'duration_id' => 1,
                    'lang' => 'ar',
                    'name' => 'ساعة',
                    'plural_name' => 'ساعات',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 5,
                    'duration_id' => 2,
                    'lang' => 'en',
                    'name' => 'day',
                    'plural_name' => 'days',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 6,
                    'duration_id' => 3,
                    'lang' => 'en',
                    'name' => 'week',
                    'plural_name' => 'weeks',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 7,
                    'duration_id' => 4,
                    'lang' => 'en',
                    'name' => 'month',
                    'plural_name' => 'months',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 8,
                    'duration_id' => 5,
                    'lang' => 'en',
                    'name' => 'year',
                    'plural_name' => 'years',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 9,
                    'duration_id' => 2,
                    'lang' => 'ar',
                    'name' => 'يوم',
                    'plural_name' => 'أيام',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 10,
                    'duration_id' => 3,
                    'lang' => 'ar',
                    'name' => 'أسبوع',
                    'plural_name' => 'أسابيع',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 11,
                    'duration_id' => 4,
                    'lang' => 'ar',
                    'name' => 'شهر',
                    'plural_name' => 'شهور',
                    'created_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 12,
                    'duration_id' => 5,
                    'lang' => 'ar',
                    'name' => 'سنة',
                    'plural_name' => 'سنوات',
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
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
        Schema::dropIfExists('st_durations_translations');
    }
};
