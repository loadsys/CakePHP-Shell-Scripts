# Loadsys Cake Command Line Scripts #

**DISCLAIMER: We use these tools ourselves, but that doesn't mean they will necessarily work for you in your situation. This repo is public in case it can be of use to anyone else (and because it's convenient for us), but it _is not supported_ and _may change without notice_. Issues and contributions may be flat out ignored if they don't impact us. You have been warned!**

This collection of scripts is intended to provide consistency and shortcuts for common project tasks (currently they are tuned for CakePHP v2.x projects, pre-Composer). Key tools are highlighted and described below.

* Most scripts listed below can take a `-h` option as their first argument to output usage information.
* Most are designed to run with few or no arguments; they try to guess sensible defaults whenever possible.


## Installation ##

**WARNING!** These scripts are currently only compatible with Cake 2.x. Don't try to use them on a 1.x project.

The scripts all expect to live together in a subfolder of your project root named `bin/`. To install them, navigate to your project root and run:

```bash
git submodule add https://github.com/loadsys/CakePHP-Shell-Scripts.git bin
```
The `bin` at the end is critical. The scripts expect to be able to call each other in a folder directly inside the project root named `bin/`.


## Getting Updates ##

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

1. Checkout a copy of the Loadsys CakePHP-Skeleton, which includes this project as a submodule. (This arrangement is useful for the ability to test the scripts  against the CakePHP-Skeleton project itself.)
2. @TODO What's the actual process from here? Edit the submodule?


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


### bin/add-cakephp-version and bin/symlink-cake-core ###
The first script automates the process of fetching and preparing local copies of the CakePHP core project.

The second automates the Loadsys standard practice of not including Cake core files in the repo and instead symlinking to a local Cake core. (Will eventually become moot as we move to composer.)

