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
            $table->renameColumn('danger_value', 'threshold_level');
            $table->renameColumn('danger_level_unit', 'threshold_unit');
            $table->renameColumn('last_danger_notified_at', 'last_threshold_notified_at');
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
            $table->renameColumn('threshold_level', 'danger_value');
            $table->renameColumn('threshold_unit', 'danger_level_unit');
            $table->renameColumn('last_threshold_notified_at', 'last_danger_notified_at');
        });
    }
};
