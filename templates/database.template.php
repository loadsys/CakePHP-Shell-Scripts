<?php
// warning(`This file is used as an m4 template for bin/db-writeconfig! Be careful modifying the contents!')
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'DB_HOST',
		'login' => 'DB_USER',
		'password' => 'DB_PASS',

		'database' => 'DB_NAME',
		'prefix' => 'DB_PREFIX',
		//'encoding' => 'utf8',
	);

	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'DB_HOST',
		'login' => 'DB_USER',
		'password' => 'DB_PASS',

		'database' => 'DB_NAME_TEST',
		'prefix' => 'DB_PREFIX',
		//'encoding' => 'utf8',
	);
}
