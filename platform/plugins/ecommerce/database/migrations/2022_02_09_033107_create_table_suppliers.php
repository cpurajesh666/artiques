<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Botble\Ecommerce\Enums\CustomerStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('avatar', 255)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('website', 250)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('status', 60)->default(CustomerStatusEnum::ACTIVATED);
            $table->rememberToken();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ec_suppliers');
    }
};
