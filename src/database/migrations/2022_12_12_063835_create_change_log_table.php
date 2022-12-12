<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('action_type', 64);
            $table->string('table_name', 128);
            $table->longText('old_value')->nullable(true);
            $table->longText('new_value')->nullable(true);
            $table->integer('created_by')->default(0);
            $table->timestamp('created_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_log');
    }
}
