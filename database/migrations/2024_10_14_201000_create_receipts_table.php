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
        Schema::create('receipts', function (Blueprint $table) {
            $table->integer('number')->default(1);
            $table->integer('year')->default(1);
            $table->text('date');
            $table->integer('customers_id');
            $table->integer('rates_id');
            $table->float('total', 10, 0);
            $table->text('created_at')->nullable();
            $table->text('updated_at')->nullable();
            $table->boolean('custom_quotas')->default(false);
            $table->integer('payment_type_id')->default(1);
            $table->integer('num_people')->default(1);

            $table->primary(['number', 'year']);
            $table->index(['year', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
};
