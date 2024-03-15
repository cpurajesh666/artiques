<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_sku_lookup', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->string('sku_text', 60);
            $table->integer('sku_number')->unsigned();
            $table->string('sku', 60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_sku_lookup');
    }
};
