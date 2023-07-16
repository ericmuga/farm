<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        // Get all the existing table names
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        // Loop through each table and add the extra fields
        foreach ($tableNames as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->json('created_geolocation')->nullable();
                $table->json('updated_geolocation')->nullable();
                $table->unsignedBigInteger('created_by_user_id')->nullable();
                $table->unsignedBigInteger('updated_by_user_id')->nullable();
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
        // Get all the existing table names
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        // Loop through each table and remove the extra fields
        foreach ($tableNames as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn([
                    'created_geolocation',
                    'updated_geolocation',
                    'created_by_user_id',
                    'updated_by_user_id',
                ]);
            });
        }
    }

};


