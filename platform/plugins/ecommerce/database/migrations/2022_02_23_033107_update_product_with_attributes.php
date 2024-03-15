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
        Schema::table('ec_product_with_attribute', function (Blueprint $table) {
            $table->integer('attribute_set_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_product_with_attribute', function (Blueprint $table) {
            $table->dropColumn([
                'attribute_set_id',
            ]);
        });
    }
};
