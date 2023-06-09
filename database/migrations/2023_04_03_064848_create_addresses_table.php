<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{

	public function up()
	{
		Schema::create('addresses', function (Blueprint $table) {
			$table->id();
            $table->foreignId('user_id');
            $table->foreignId('city_id');
			$table->string('location');
            $table->string('postal_code');
            $table->string('location_name');
            $table->string('building_number');
            $table->enum('location_type', ['home', 'work'])->default('home');
			$table->decimal('latitude', 10,8);
			$table->decimal('longitude', 10,8);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('addresses');
	}
};
