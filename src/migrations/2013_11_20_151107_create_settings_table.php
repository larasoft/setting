<?php

use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function($table)
		{
	    	$table->increments('id');
	    	$table->string('key');
	    	$table->longText('value')->default('');
	    	$table->tinyInteger('serialized')->default(0);
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
		//
	}

}