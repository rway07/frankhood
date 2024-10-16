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
        Schema::table('funerals_cost_exceptions', function (Blueprint $table) {
            $table->foreign(['rate_id'], '')->references(['id'])->on('rates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['customer_id'], '')->references(['id'])->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funerals_cost_exceptions', function (Blueprint $table) {
            $table->dropForeign('');
            $table->dropForeign('');
        });
    }
};
