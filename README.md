# Loadsys Cake Command Line Scripts

**DISCLAIMER: We use these tools ourselves, but that doesn't mean they will necessarily work for you in your situation. This repo is public in case it can be of use to anyone else (and because it's convenient for us), but it _is not supported_ and _may change without notice_. Issues and contributions may be flat out ignored if they don't impact us. You have been warned!**

This collection of scripts is intended to provide consistency and shortcuts for common project tasks. Key tools are highlighted and described below.

* The master branch is meant for use with Cake 3 projects exclusively.
* The 2.x branch is meant for Cake 2.x projects that use composer (git submodules are still supported).
* The 1.x branch is tuned for Cake 1.x projects that use git submodules for dependency management.
* Most scripts listed below can take a `-h` option as their first argument to output usage information.
* Most are designed to run with few or no arguments; they try to guess sensible defaults whenever possible.
* Most are designed to fail gracefully with a non-zero exit code.


## Requirements

The following dependencies are assumed to be available on the target system and available via the default `$PATH`.

* `bash`
* `composer`
* `git`
* `mail`
* `mysqldump`
* `php`
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

**Note**: The `bin-dir` is non-standard and may cause conflicts with other composer packages that install "binaries". These scripts are all expect to live in `PROJECT_ROOT/bin` though so don't expect anything to work if you forgo the `bin-dir` setting in your project.


## Contributing Improvements

**WARNING: Outside contributions are appreciated, but may be rejected if they do not impact our usage of these tools. Please consider this before sending a pull request.**

There currently is not a convenient way to set up a test harness around this repo.

1. The best thing to do is to clone this repo (or a fork) into the `bin/` folder of an existing and established project so you have something to test against.
1. Make your edits to the scripts as necessary and commit to a topic branch.
1. If you have **added** new scripts, make sure they are executable and run `./composer-binaries` to update the composer.json file automatically (you should be using PHP 5.4+ to make proper use of the `JSON_PRETTY_PRINT` flag).
1. Run `composer validate` to run a syntax check on the json file.
1. Submit a pull request for your branch.


## Notable Scripts


### bin/update

Automates all of the steps for a read-only copy of the app (such as staging or production) to pull new code from the repo and update the local running copy. It performs tasks like:

* Checking the local working copy to make sure no changes have been made from the checked-out commit that might prevent an automatic pull or merge.
* Backing up the active database (crudely, but effective for smallish apps).
* On older projects, checking for db_updates.sql changes that should be applied before merging new code and pausing to display them to the user before proceeding.
* Pulling and merging code from the remote repo.
* Applying Migrations, if present.
* Clearing Cake cache directories.
* Copying over environment-specific configs, if present.
* Updating git submodules.
* Ensuring file ownership and write permissions are still correct.
* Showing the user the new active commit's log.


### bin/db-login

A simple shortcut script that uses the `[Datasources][default]` key defined in `config/app.php` to start a command line `mysql` session for you. Incredibly convenient in a production environment to run manual data queries during troubleshooting.


### bin/db-backup

Uses the default database credentials in `config/app.php` to create a ZIPed `mysqldump` of that database in a local `backups/` folder. Helpful because you don't have to specify DB credentials. It also reports the ZIP size to help you keep a mental tally over time.


### @TODO:

Add cache-clear, tests-run, docs-generate, codesniffer-run here as notable scripts.


## License

MIT


## Copyright

Copyright 2015 Loadsys Web Strategies
