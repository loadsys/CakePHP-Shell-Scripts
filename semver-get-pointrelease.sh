#!/usr/bin/env bash
# Search a list of local git tags for the "latest" matching the input argument.
# Input must be in the form of: x.y
# Can't rely on `sort -V` since it requires GNU coreutils.

if [ -z $1 ]; then
	exit 1
fi

if [ -z $2 ]; then
	REPO_DIR=.
else
	REPO_DIR=$2
fi

git --work-tree="$REPO_DIR" --git-dir="$REPO_DIR/.git" tag -l 1>&2

MAJOR_MINOR=$1
POINTRELEASE=$( git --work-tree="$REPO_DIR" --git-dir="$REPO_DIR/.git" tag -l | grep "^$MAJOR_MINOR" | sed "s/$MAJOR_MINOR\.//" | sort -n | tail -1 )


if [ -z $POINTRELEASE ]; then
	exit 2
fi

echo "$MAJOR_MINOR.$POINTRELEASE"
