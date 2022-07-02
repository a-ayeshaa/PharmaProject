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
        Schema::create('contract', function (Blueprint $table) {
            $table->id('contract_id');
            $table->integer('vendor_id');
            $table->string('vendor_name');
            $table->integer('cart_id');
            $table->dateTime('accepted_time');
            $table->dateTime('delivery_time');
            $table->string('contract_status')->defalut('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract');
    }
};
