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
        Schema::create('customers_receipts', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('customers_id');
            $table->integer('number')->default(1);
            $table->integer('year')->default(1);
            $table->float('quota', 10, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_receipts');
    }
};
