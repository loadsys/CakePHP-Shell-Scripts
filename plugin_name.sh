#!/usr/bin/env bash
# Attempts to guess the name of the plugin using the path information available.

DIR="$( cd -P "$( dirname "$0" )" >/dev/null 2>&1 && pwd )"

if [ -z "$PLUGIN_NAME" ]; then
	if [[ "$0" = *Plugin/* ]]; then
		PLUGIN_NAME="$0"
		# Strip everything before the plugin name.
		PLUGIN_NAME="${PLUGIN_NAME##*Plugin/}"
		# Strip everything after the first path component.
		PLUGIN_NAME="${PLUGIN_NAME%%/*}"
	else
		# Use the folder name one "up" from the build/ folder.
		PLUGIN_NAME="$( cd -P "$( dirname "$0" )"/.. >/dev/null 2>&1 && basename `pwd` )"
	fi
fi

echo $PLUGIN_PATH

if [ -z "$PLUGIN_NAME" ]; then
	exit 1
fi
