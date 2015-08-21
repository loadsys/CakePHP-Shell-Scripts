# Loadsys Cake Command Line Scripts

This collection of scripts is intended to provide consistency and shortcuts for common project tasks. Key tools are highlighted and described below. Coordinates heavily with the [Loadsys CakePHP App Skeleton](https://github.com/loadsys/CakePHP-Skeleton).

* This is the `master` branch and along with the `3.x.x` releases is meant for use with Cake 3 projects exclusively.
* The [`cake-2.x`](https://github.com/loadsys/CakePHP-Shell-Scripts/tree/cake-2.x) branch and `2.x.x` releases are meant for Cake 2.x projects that use composer (git submodules are still supported).


* Most scripts listed below can take a `-h` option as their first argument to output usage information.
* Most are designed to run with few or no arguments; they try to guess sensible defaults whenever possible.
* Most are designed to fail gracefully with a non-zero exit code.


## Requirements

The following dependencies are assumed to be available on the target system and available via the default `$PATH`.

* `bash`
* `composer`
* `git`
* `mail`
* `mysql`
* `mysqldump`
* `php` (v5.6 recommended)
* `realpath` (not available by default on OS X)
* `readlink`
* `zip`

Additionally, some scripts expect additional tools that should be automatically installed as composer dependencies to _this_ package:

* `bin/phpcs`
* `bin/phpunit`
* `bin/phpdoc`
* `bin/cake` (composer-installed **by target project**)

If these items are not available, some scripts may not function as expected.


## Installation

**WARNING!** These scripts are currently only compatible with Cake 3.x. Don't try to use them on anything else.

Your project's `composer.json` file should include something like this:

```json
{
	"require": {
		"loadsys/cakephp-shell-scripts": "~3.0"
	},
	"config": {
		"bin-dir": "bin"
	}
}
```

Then run `composer install` to pull this repo into your project. A `bin/` folder should be created in your project root with symlinks to all of the scripts from this package.

**Note**: The `bin-dir` is non-standard and may cause conflicts with other composer packages that install "binaries". The bundled scripts all expect to live in `PROJECT_ROOT/bin` though so don't expect anything to work if you forgo the `bin-dir` setting in your project.


## Contributing Improvements

**WARNING: Outside contributions are appreciated, but may be rejected if they do not impact our usage of these tools. Please consider this before sending a pull request.**

There currently is not a convenient way to set up a test harness around this repo.

1. The best thing to do is to clone this repo (or a fork) into the `bin/` folder of an existing and established project so you have something to test against.
1. Make your edits to the scripts as necessary and commit to a topic branch.
1. If you have **added** new scripts, make sure they are executable and run `./composer-binaries` to update the composer.json file automatically (you should be using PHP 5.4+ to make proper use of the `JSON_PRETTY_PRINT` flag).
1. Run `composer validate` to run a syntax check on the json file.
1. Submit a pull request for your branch.


## Notable Scripts


### bin/cache-clear

Uses the new `ConsoleShell` to iterate over all of the configured Caches in your app and clear each one.


### bin/codesniffer-run

Wraps the call to PHPCodeSniffer so that it can be called from inside or outside a Vagrant VM. Also checks the `installed_paths` configuration of phpcs and injects the necessary paths for the CakePHP and Loadsys coding standards if they are not already present.


### bin/coverage-ensure

Reads the location of the `clover.xml` file generated from your phpunit runs from your `phpunit.xml[.dist]` file, then examines that file for how much of your code is covered. Takes an integer command line argument representing the minimum required percentage, and returns with an exit status non-zero if your coverage is lacking. Intended for use during automated testing runs, such as on Travis.


### bin/db-backup

Uses the default database credentials in `config/app.php` to create a ZIPed `mysqldump` of that database in a local `backups/` folder. Helpful because you don't have to specify DB credentials. It also reports the ZIP size to help you keep a mental tally over time.

Can be used manually for one-off backups before dangerous operations (such as a code deploy and DB migrations), or automatically (such as in a Vagrant VM shutdown script, to preserve an internal DB and protect it from `vagrant destroy`.)


### bin/db-login

A simple shortcut script that uses the `[Datasources][default]` key defined in `config/app.php` to start a command line `mysql` session for you. Incredibly convenient in a production environment to run manual data queries during troubleshooting.

Also properly handles I/O redirection, so any time you would normally run `mysql --user=user -ppass --host=host --port=3306 database_name < import.sql` you can instead just run `bin/db-login < import.sql` and never have to worry about the connection credentials. Great for scripting and provisioning.


### bin/deploy

Automates all of the steps for a read-only copy of the app (such as staging or production) to pull new code from the repo and update the local running copy. It performs tasks like:

* Checking the local working copy to make sure no changes have been made from the checked-out commit that might prevent an automatic pull or merge.
* Backing up the active database (crudely, but effective for smallish apps).
* Pulling and merging code from the remote repo.
* Applying Migrations, if present.
* Clearing Cake cache directories.
* Copying over environment-specific configs, if present.
* Updating git submodules and/or composer dependencies.
* Ensuring file ownership and write permissions are still correct.
* Showing the user the new active commit's log and optionally generating a notification email.


### bin/docs-generate

Wraps the call to phpDocumentor so that it can be called from inside or outside a Vagrant VM but always executed inside.


### bin/tests-run

Intended to serve as a convenience method for executing phpunit since it's callable from your host machine and will execute tests inside of vagrant in that case.

It can also take a partial filename as an argument and run the corresponding test case directly. This mode of use is intended to be paired with a file watcher, like [kicker](https://github.com/alloy/kicker), [grunt](http://gruntjs.com/) or [efsw](https://bitbucket.org/SpartanJ/efsw). When a source file or its test case counterpart is changed, the tests for that single file can be executed automatically inside the VM.


## License

[MIT](LICENSE.md)

**DISCLAIMER: We use these tools ourselves, but that doesn't mean they will necessarily work for you in your situation. This repo is public in case it can be of use to anyone else (and because it's convenient for us), but it _is not supported_ and _may change without notice_. Issues and contributions may be ignored if they don't impact us. You have been warned!**


## Copyright

Copyright &copy; 2015 [Loadsys Web Strategies](http://loadsys.com)
