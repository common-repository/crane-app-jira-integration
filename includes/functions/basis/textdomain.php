<?php
defined( 'ABSPATH' ) or die();

/**
 * Functions to manage textdomain
 *
 * @author Kai Krannich
 * @version 1.0
 */

/*
 * Loading textdomain
 */
function ca_ji_load_textdomain() {
	// Loading textdomain
	load_plugin_textdomain( 'crane-app-jira-integration', false, 'crane-app-jira-integration/languages' );
}
add_action( 'init', 'ca_ji_load_textdomain' );