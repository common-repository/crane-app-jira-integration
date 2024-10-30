<?php
defined( 'ABSPATH' ) or die();

/**
 * Functions to manage plugin page
 *
 * @author Kai Krannich
 * @version 1.0
 */

/**
 * Adding action links
 *
 * @param $actions
 *
 * @return array
 */
function ca_ji_add_action_links( $actions ) {
	// Action links
	$actions[] = '<a href="' . admin_url( 'options-general.php?page=ca_ji' ) . '">' . esc_html( __( 'Settings', 'crane-app-jira-integration' ) ) . '</a>';
	// Outputting action links
	return $actions;
}
add_filter( 'plugin_action_links_' . ca_ji_get_plugin_file(), 'ca_ji_add_action_links' );