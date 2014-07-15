# Loadsys Cake Command Line Scripts #

**DISCLAIMER: We use these tools ourselves, but that doesn't mean they will necessarily work for you in your situation. This repo is public in case it can be of use to anyone else (and because it's convenient for us), but it _is not supported_ and _may change without notice_. Issues and contributions may be flat out ignored if they don't impact us. You have been warned!**

This collection of scripts is intended to provide consistency and shortcuts for common project tasks. Key tools are highlighted and described below.

* The 1.x branch is tuned for projects that primarily use git submodules for dependency management.
* The 2.x branch is meant for projects that use composer (although submodules are still supported).
* Most scripts listed below can take a `-h` option as their first argument to output usage information.
* Most are designed to run with few or no arguments; they try to guess sensible defaults whenever possible.
* Most are designed to fail gracefully. For example, if `pear` is available and your project defines a pear config file, then those dependencies will be installed by `deps-install`, otherwise pear will be silently ignored. This provides maximum flexibility without having to customize the scripts per-project.


## Requirements ##

The following dependencies are assumed to be available on the target system and available in the default `PATH`.

* `bash`
* `composer`
* `git`
* `mail`
* `mysqldump`
* `pear`
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


## Installation ##

**WARNING!** These scripts are currently only compatible with Cake 2.x. Don't try to use them on a 1.x project.

### composer ###

Your project's own `composer.json` file should look something like this:

```json
{
    "require": {
		"loadsys/cakephp-shell-scripts": "2.0.*"
    },
    "config": {
		"bin-dir": "bin"
    }
}
```
Then run `composer install` to pull this repo into your project. A `bin/` folder should be created in your project root with symlinks to all of the scripts from this package.

**Note**: The `bin-dir` is non-standard and may cause conflicts with other composer packages that install "binaries". These scripts are all expect to live in `PROJECT_ROOT/bin` though so don't expect anything to work if you forgo the `bin-dir` setting in your project.

### git submodule ###

(This is the old method and will eventually be retired.)

The scripts all expect to live together in a subfolder of your project root named `bin/`. To install them, navigate to your project root and run:

```bash
git submodule add https://github.com/loadsys/CakePHP-Shell-Scripts.git bin
```
As mentioned above, the `bin` at the end is critical. The scripts expect to be able to call each other in a folder directly inside the project root named `bin/`.


### Getting Submodule Updates ###

To pull any recent changes to the script library into your project, use the `bin/bin-selfupdate` script to update the submodule to the latest release and add the changed commit to the parent repo for you to commit.

```bash
bin/bin-selfupdate
```

If your bin/ dir does not yet have the bin-selfupdate script, you can run the following commands manually the first time:

```bash
cd bin
git pull origin master
cd ..
git add bin
git commit -m "Updated bin submodule to latest release."
```

The above changes the active commit for the submoduled bin repo, which will then be applied to other copies of the project when `git submodule update` is executed (either by `bin/update`, `bin/init-repo` or manually.)


## Contributing Improvements ##

**WARNING: Outside contributions are appreciated, but may be ignored if they do not impact our usage of these tools. Please consider this before sending a pull request.**

There currently is not a convenient way to set up a test harness around this repo.

1. The best thing to do is to clone this repo (or a fork) into the `bin/` folder of an existing and established project so you have something to test against.
1. Make your edits to the scripts as necessary and commit to a feature branch.
1. If you have **added** new scripts, make sure they are executable and run `./composer-binaries` to update the composer.json file automatically (you should be using PHP 5.4 to make proper use of the `JSON_PRETTY_PRINT` flag).
1. Run `composer validate` to run a syntax check on the json file.
1. Submit a pull request for your branch.


## Issues ##

@TODO: Explanations below are off the cuff and need refinement.

Need to talk about the consequences and delays in how changes to this repo affect script runs in projects that include it.

For example, take a project that has already included this repo as a submodule but hasn't been updated for a while (and we've made changes to this bin repo in the meantime). When that project runs `bin/update` to update the staging copy of the app, it will be the "stale" version of bin/update that executes. That version of bin/update will eventually trigger a `git submodule update`, which might pull in changes to _itself_-- but too late for the current runtime.

The bigger issue is that if a change to a bin/ script happens DURING the run of the stale `update` script as a result of the included `git submodules update` call, and the changes to that dependency script are incompatible with the stale version of `update`, the run will fail in unpredictable ways.

The script really needs a way of updating JUST the CakePHP-Shell-Scripts submodule **first** (and no other submodules that probably expect the codebase to be changing along with it), and if it detects any changes have been applied, exits and re-calls the newer version of itself. Seems like there is a potential for an infinite loop here if the change detection is done wrong though.

This may all be far less of an issue when we switch to composer-based installations, since it will handle the "bootstrapping" for us.


## Key Scripts ##

### bin/init-repo ###

Designed to perform all of the steps necessary to initialize a project for a developer who has just cloned the repo for the first time. (It should never be needed after that.) It performs tasks like:

* Sym-linking a Cake core into the project, if necessary.
* Copying default config files into place for the given environment.
* Writing database connection details into the `database.php` config file.
* Setting project folder ownership and write permissions.
* Initializing and updating any included git submodules.
* Applying database migrations, if present.
* Offering to load Seed data, if present.

Given an existing web-hosted folder and a database ready for use, it should fully prep a newly cloned copy of the project for use, or development.


### bin/update ###

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


### bin/db-login ###

A simple shortcut script that uses the contents of `Config/database.php` to start a command line `mysql` session for you. Incredibly convenient in a production environment to run manual data queries during troubleshooting.


### bin/db-backup ###

Uses the default credentials in `Config/database.php` to create a ZIPed `mysqldump` of that database into a local `backups/` folder. Helpful because you don't have to specify DB credentials in multiple places.
