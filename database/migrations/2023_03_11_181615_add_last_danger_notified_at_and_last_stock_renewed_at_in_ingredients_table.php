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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->after('danger_level_unit', function () use ($table) {
                $table->dateTime('last_stock_renewed_at')->nullable();
                $table->dateTime('last_danger_notified_at')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn([
                'last_stock_renewed_at',
                'last_danger_notified_at',
            ]);
        });
    }
};
