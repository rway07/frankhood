<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('priori')) {
            return;
        }

        Schema::create('priori', function (Blueprint $table) {
            $table->increments('id_priore');
            $table->integer('customer_id');
            $table->unsignedSmallInteger('election_year');
            $table->unsignedSmallInteger('votes')->default(0);
            $table->unsignedSmallInteger('total_votes')->default(0);
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('NO ACTION')
                ->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priori');
    }
};
