<?php
/**
 * Database connection configuration.
 *
 * warning(`This file is used as an m4 template for bin/db-writeconfig! Be careful modifying the contents!')
 */
class DATABASE_CONFIG {

	/**
	 * Default configuration. Should be suitable for developer "local"
	 * installations, although you should really use vagrant.
	 *
	 * @access	public
	 * @var	array	$default
	 */
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

	/**
	 * Vagrant configuration. These settings match those in
	 * `Lib/puphpet/config.yaml` for the MySQL server that is set up in the
	 * Vagrant VM.
	 *
	 * @access	public
	 * @var	array	$vagrant
	 */
	public $vagrant = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'vagrant',
		'password' => 'vagrant',

		'database' => 'vagrant',
		'prefix' => '',
		//'encoding' => 'utf8',
	);

	/**
	 * Test configuration. In spite of the constructor below, the TestShell
	 * should still selectively load the `::$test` config. Tests should be
	 * run from inside the vagrant VM.
	 *
	 * @access	public
	 * @var	array	$test
	 */
	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'vagrant',
		'password' => 'vagrant',

		'database' => 'vagrant_test',
		'prefix' => '',
		//'encoding' => 'utf8',
	);

	/**
	 * Production configuration. Intended to point multiple app server
	 * instances at a shared AWS RDS instance. Because it depends on
	 * environment variables, it must be set up in `__construct()` below.
	 * Ref: http://docs.aws.amazon.com/elasticbeanstalk/latest/dg/create_deploy_PHP.rds.html
	 *
	 * @access	public
	 * @var	array	$production
	 */
	public $production = null;

	/**
	 * Set up dynmaic configs and set the appropriate configuration based on
	 * the APP_ENV environment variable.
	 *
	 * If the APP_ENV env var is not set, or set to something that does not
	 * match one of the available configurations above, 'default' will be used.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct() {

		// Define any configs that depend on dynamic input, such as $_SERVER vars.
		// We also want to silently ignore if these vars aren't set in any other environment.
		$this->production = array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => @$_SERVER['RDS_HOSTNAME'],
			'port' => @$_SERVER['RDS_PORT'],
			'login' => @$_SERVER['RDS_USERNAME'],
			'password' => @$_SERVER['RDS_PASSWORD'],

			'database' => @$_SERVER['RDS_DB_NAME'],
			'prefix' => '',
			//'encoding' => 'utf8',
		);

		// Determine which config is the "default" for the given environment.
		$available = array_keys(get_class_vars('DATABASE_CONFIG'));
		$env = getenv('APP_ENV');
		$env = (in_array($env, $available) ? $env : 'default');
		$this->default = $this->{$env};
	}
}
