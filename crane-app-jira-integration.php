<?php
defined( 'ABSPATH' ) or die();

/*
Plugin Name: CRANE APP Jira Integration
Plugin URI: https://www.crane-app.de/wordpress-entwicklung/plugins/
Description: The WordPress Plugin <strong>Jira Integration</strong> is developed by <a href="https://www.crane-app.de/" target="_blank">CRANE APP</a> from Dresden. With <strong>Jira Integration</strong> it is possible to display Jira contents on your website. This is probably the easiest way to provide external access to your Jira contents.
Version: 1.1.0
Author: Kai Krannich
Author URI: https://www.crane-app.de/team/
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.htmlText
Domain: crane-app-jira-integration
Domain Path: /languages

CRANE APP Jira Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation version 3 of the License.

CRANE APP Jira Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with CRANE APP Jira Integration. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
*/

// Getting plugin directory
function ca_ji_get_plugin_dir() {
	return plugin_dir_path( __FILE__ );
}

// Getting plugin URL
function ca_ji_get_plugin_url( $path = '' ) {
	return plugins_url( $path, __FILE__ );
}

// Getting plugin file
function ca_ji_get_plugin_file() {
	return plugin_basename( __FILE__ );
}

// Including all functions
require_once( 'includes/functions/all.php' );