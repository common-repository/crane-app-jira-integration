<?php
defined( 'ABSPATH' ) or die();

/**
 * Functions to manage scripts
 *
 * @author Kai Krannich
 * @version 1.0
 */

/**
 * Enqueuing scripts
 */
function ca_ji_enqueue_scripts() {
	// Plugin URL
	$plugin_url = ca_ji_get_plugin_url( 'public/' );
	// Dashicons
	wp_enqueue_style( 'dashicons' );
	// Getting option
	$option = get_option( 'ca_ji_design' );
	// Checking if Bootstrap is enabled
	if ( ! isset( $option['bootstrap'] ) ) {
		// jQuery UI
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'ca_jquery_ui_css_v1121', $plugin_url . 'css/external/jquery-ui/jquery-ui.min.css', array(), null );
		// Plugin
		wp_enqueue_script( 'ca_ji_js', $plugin_url . 'js/crane-app-jira-integration.js', array( 'jquery-ui-dialog' ), null, true );
		// Localizing script
		wp_localize_script( 'ca_ji_js', 'ca_ji',
			array(
				'close_text' => esc_html( __( 'Close', 'crane-app-jira-integration' ) ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'ca_ji_enqueue_scripts' );