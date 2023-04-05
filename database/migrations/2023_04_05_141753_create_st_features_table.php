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
        if (!Schema::hasTable('st_features')) {
            Schema::create('st_features', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 100)->unique('slug')->comment('A unique name for feature, eg: access_specific_section, ad_free ..');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            DB::table('st_features')->insert([
                [
                    'id' => 1,
                    'slug' => 'free_books_access',
                    'created_at' => '2023-03-21 22:39:32',
                    'updated_at' => '2023-03-21 22:39:32',
                ],
                [
                    'id' => 2,
                    'slug' => 'premium_books_access',
                    'created_at' => '2023-03-21 22:41:04',
                    'updated_at' => '2023-03-21 22:41:04',
                ],
                [
                    'id' => 3,
                    'slug' => 'exporting_search_results',
                    'created_at' => '2023-03-21 22:41:04',
                    'updated_at' => '2023-03-21 22:41:04',
                ],
                [
                    'id' => 4,
                    'slug' => 'saving_search_queries',
                    'created_at' => '2023-03-21 22:41:04',
                    'updated_at' => '2023-03-21 22:41:04',
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
        Schema::dropIfExists('st_features');
    }
};
