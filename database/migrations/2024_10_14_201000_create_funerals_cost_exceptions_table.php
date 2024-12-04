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
        if (Schema::hasTable('funerals_cost_exceptions')) return;

        Schema::create('funerals_cost_exceptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rate_id');
            $table->integer('customer_id');
            $table->float('cost', 10, 0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funerals_cost_exceptions');
    }
};
