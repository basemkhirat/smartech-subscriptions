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
        if (!Schema::hasTable('st_features_translations')) {
            Schema::create('st_features_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('feature_id');
                $table->string('lang', 3);
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_features_translations')->insert([
                [
                    'id' => 1,
                    'feature_id' => 1,
                    'lang' => 'en',
                    'name' => 'Reading free books',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 2,
                    'feature_id' => 1,
                    'lang' => 'ar',
                    'name' => 'قراءة الكتب المجانية',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 3,
                    'feature_id' => 2,
                    'lang' => 'en',
                    'name' => 'Reading premium books',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 4,
                    'feature_id' => 2,
                    'lang' => 'ar',
                    'name' => 'قراءة الكتب المدفوعة',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 5,
                    'feature_id' => 3,
                    'lang' => 'en',
                    'name' => 'Exporting search results',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ], [
                    'id' => 6,
                    'feature_id' => 3,
                    'lang' => 'ar',
                    'name' => 'تصدير نتائج البحث',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 7,
                    'feature_id' => 4,
                    'lang' => 'en',
                    'name' => 'Saving search queries',
                    'description' => NULL,
                    'created_at' => '2023-03-21 22:40:33',
                    'updated_at' => '2023-03-21 22:40:33',
                ],
                [
                    'id' => 8,
                    'feature_id' => 4,
                    'lang' => 'ar',
                    'name' => 'تسجيل عمليات البحث',
                    'description' => NULL,
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
        Schema::dropIfExists('st_features_translations');
    }
};
