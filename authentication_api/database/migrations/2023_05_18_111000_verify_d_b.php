<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('verifyDB')) {
            Schema::create('verifyDB', function (Blueprint $table) {
                $table->increments('row_id');
                $table->string('id');
                $table->string('file_type');
                $table->string('verification_result');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('verifyDB');
    }
};
