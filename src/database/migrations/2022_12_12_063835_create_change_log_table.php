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
            $table->string('table_pk', 128)->nullable(true);
            $table->string('table_pk_value', 128)->nullable(true);
            $table->longText('old_value')->nullable(true);
            $table->longText('new_value')->nullable(true);
            $table->text('controller')->nullable(true);
            $table->string('route_name', 255)->nullable(true);
            $table->text('req_url')->nullable(true);
            $table->text('req_method')->nullable(true);
            $table->string('req_ip', 24)->nullable(true);
            $table->text('req_user_agent')->nullable(true);
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
