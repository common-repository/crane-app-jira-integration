<?php
defined( 'ABSPATH' ) or die();

/**
 * Functions to manage REST API
 *
 * @author Kai Krannich
 * @version 1.1.0
 */

/**
 * Cookie-based authenticating
 *
 * @return object|WP_Error
 */
function ca_ji_jira_rest_cookie_auth() {
	// New instance of WP_Error
	$errors = new WP_Error();
	// Getting option
	$option = get_option( 'ca_ji_authentication' );
	// Checking if data exists
	if ( ! isset( $option['jira_base_url'] ) || ! isset( $option['jira_username'] ) || ! isset( $option['jira_password'] ) ) {
		$errors->add( 'nonexisting_data', __( 'The data does not exist.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Checking data types
	if ( ! is_string( $option['jira_base_url'] ) || ! is_string( $option['jira_username'] ) || ! is_string( $option['jira_password'] ) ) {
		$errors->add( 'invalid_data_types', __( 'The data types are not valid.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Jira base URL
	$jira_base_url = trim( $option['jira_base_url'] );
	// Jira username
	$jira_username = trim( $option['jira_username'] );
	// Jira password
	$jira_password = trim( $option['jira_password'] );
	// Checking if data is empty
	if ( empty( $jira_base_url ) || empty( $jira_username ) || empty( $jira_password ) ) {
		$errors->add( 'empty_data', __( 'The data is empty.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Initializing a cURL session and returning a cURL handle
	$ch = curl_init();
	// Checking if errors occur
	if ( $ch === false ) {
		$errors->add( 'errors_curl', __( 'Errors occurred within cURL functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Data to post
	$postfields = array(
		'username' => $jira_username,
		'password' => $jira_password
	);
	// Getting JSON representation
	$postfields = json_encode( $postfields );
	// Checking if errors occur
	if ( $postfields === false ) {
		$errors->add( 'error_php_functions', __( 'Errors occurred within PHP functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Options for cURL session
	$options = array(
		CURLOPT_HTTPHEADER     => array(
			'Content-type: application/json'
		),
		CURLOPT_POST           => true,
		CURLOPT_POSTFIELDS     => $postfields,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL            => $jira_base_url . 'rest/auth/1/session'
	);
	// Setting multiple options for cURL session
	$result = curl_setopt_array( $ch, $options );
	// Checking if errors occur
	if ( $result === false ) {
		$errors->add( 'errors_curl', __( 'Errors occurred within cURL functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Executing the cURL session
	$result = curl_exec( $ch );
	// Checking if errors occur
	if ( $result === false ) {
		$errors->add( 'errors_curl', __( 'Errors occurred within cURL functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Closing the cURL session and deleting the cURL handle
	curl_close( $ch );
	// Decoding the JSON string
	$session_cookie = json_decode( $result );
	// Checking if errors occur
	if ( $session_cookie === null ) {
		$errors->add( 'errors_php_functions', __( 'Errors occurred within PHP functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Checking data types
	if ( ! is_object( $session_cookie ) ) {
		$errors->add( 'invalid_data_types', __( 'The data types are not valid.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Checking if errors occur
	if ( isset( $session_cookie->errorMessages ) ) {
		// Logging errors
		if ( WP_DEBUG === true && WP_DEBUG_LOG === true ) {
			if ( is_array( $session_cookie->errorMessages ) ) {
				foreach ( $session_cookie->errorMessages as $errorMessage ) {
					error_log( $errorMessage );
				}
			}
		}
		$errors->add( 'errors_rest', __( 'Errors occurred within the REST API.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Outputting session cookie
	return $session_cookie;
}

/**
 * Testing REST API
 *
 * @return object|WP_Error
 */
function ca_ji_jira_rest_test() {
	// New instance of WP_Error
	$errors = new WP_Error();
	// Getting option
	$option = get_option( 'ca_ji_authentication' );
	// Checking if data exists
	if ( ! isset( $option['jira_base_url'] ) ) {
		$errors->add( 'nonexisting_data', __( 'The data does not exist.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Checking data types
	if ( ! is_string( $option['jira_base_url'] ) ) {
		$errors->add( 'invalid_data_types', __( 'The data types are not valid.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Jira base URL
	$jira_base_url = trim( $option['jira_base_url'] );
	// Checking if data is empty
	if ( empty( $jira_base_url ) ) {
		$errors->add( 'empty_data', __( 'The data is empty.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Initializing a cURL session and returning a cURL handle
	$ch = curl_init();
	// Checking if errors occur
	if ( $ch === false ) {
		$errors->add( 'errors_curl', __( 'Errors occurred within cURL functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Data to post
	$postfields = array(
		'expand' => array(
			'projects.issuetypes.fields'
		)
	);
	// Getting JSON representation
	$postfields = json_encode( $postfields );
	// Checking if errors occur
	if ( $postfields === false ) {
		$errors->add( 'error_php_functions', __( 'Errors occurred within PHP functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Options for cURL session
	$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_URL            => $jira_base_url . 'rest/api/2/issue/createmeta'
	);
	// Setting multiple options for cURL session
	$result = curl_setopt_array( $ch, $options );
	// Checking if errors occur
	if ( $result === false ) {
		$errors->add( 'errors_curl', __( 'Errors occurred within cURL functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Executing the cURL session
	$result = curl_exec( $ch );
	// Checking if errors occur
	if ( $result === false ) {
		$errors->add( 'errors_curl', __( 'Errors occurred within cURL functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Closing the cURL session and deleting the cURL handle
	curl_close( $ch );
	// Decoding the JSON string
	$test = json_decode( $result );
	// Checking if errors occur
	if ( $test === null ) {
		$errors->add( 'errors_php_functions', __( 'Errors occurred within PHP functions.', 'crane-app-jira-integration' ) );
		return $errors;
	}
	// Outputting session cookie
	return $test;
}