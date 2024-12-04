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
        if (Schema::hasTable('rates')) return;

        Schema::create('rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('year');
            $table->float('quota', 10, 0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->float('funeral_cost', 10, 0)->default(1740);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
