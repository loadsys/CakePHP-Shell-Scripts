# Loadsys Cake Command Line Scripts #

This collection of scripts is intended to provide consistency and shortcuts for common project tasks. Key tools are highlighted and described below.

* Most scripts listed below can take a '-h' option as their first argument to output usage information.
* Most are designed to run with few or no arguments, guessing sensible defaults whenever possible.

## Installation ##

**WARNING!** These scripts are currently only compatible with Cake 2.x. Don't try to use them on a 1.x project (yet).

The scripts all expect to live together in a subfolder of your project root named `bin/`. To install them, navigate to your project root and run:

```bash
git submodule add https://github.com/loadsys/bin.git bin
```

## Getting Updates ##

To pull any recent changes to the script library into your project, just run:

```bash
git submodule update
```

## Contributing Improvements ##

1. Checkout a copy of the Loadsys CakePHP-Skeleton, which includes this project as a submodule. (This arrangement is useful for the ability to test the scripts  against the CakePHP-Skeleton project itself.)
2. @TODO What's the actual process from here? Edit the submodule?


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

Given and existing web-hosted folder and a database ready for use, it should single-handedly prep a newly cloned copy of the project for use, or development.


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


### bin/spawn ###
Unique to the Skeleton (it doesn't get copied into projects spawned from the Skeleton), it automates the process of generating new blank projects from the CakePHP-Skeleton project itself.


### bin/add-cakephp-version and bin/symlink-cake-core ###
The first script automates the process of fetching and preparing local copies of the CakePHP core project.

The second automates the Loadsys standard practice of not including Cake core files in the repo and instead symlinking to a local Cake core.


