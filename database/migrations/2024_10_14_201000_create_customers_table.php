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
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('alias');
            $table->string('cf');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->string('birth_province');
            $table->string('gender');
            $table->date('death_date')->nullable();
            $table->date('revocation_date')->nullable();
            $table->string('address');
            $table->string('CAP');
            $table->string('municipality');
            $table->string('province');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->boolean('priorato');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('enrollment_year')->default('2008');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
