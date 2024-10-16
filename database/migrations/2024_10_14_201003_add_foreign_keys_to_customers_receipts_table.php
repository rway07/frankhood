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
        Schema::table('customers_receipts', function (Blueprint $table) {
            $table->foreign(['customers_id'], '')->references(['id'])->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['number', 'year'], 'customers_receipts_customers___fk')->references(['number', 'year'])->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers_receipts', function (Blueprint $table) {
            $table->dropForeign('');
            $table->dropForeign('customers_receipts_customers___fk');
        });
    }
};
