<?php
defined( 'ABSPATH' ) or die();

/**
 * Functions to manage settings
 *
 * @author Kai Krannich
 * @version 1.0
 */

/**
 * Initialising settings
 */
function ca_ji_settings_init() {
	/**
	 * Authentication
	 */
	// Registering authentication setting
	register_setting(
		'ca_ji',
		'ca_ji_authentication'
	);
	// Adding authentication section to settings page
	add_settings_section(
		'ca_ji_authentication',
		__( 'Authentication', 'crane-app-jira-integration' ),
		'ca_ji_authentication_section',
		'ca_ji'
	);
	// Adding field for Jira base URL
	add_settings_field(
		'ca_ji_jira_base_url',
		__( 'Base URL', 'crane-app-jira-integration' ),
		'ca_ji_field_jira_base_url',
		'ca_ji',
		'ca_ji_authentication',
		array(
			'label_for' => 'ca_ji_jira_base_url'
		)
	);
	// Adding field for Jira username
	add_settings_field(
		'ca_ji_jira_username',
		__( 'Username', 'crane-app-jira-integration' ),
		'ca_ji_field_jira_username',
		'ca_ji',
		'ca_ji_authentication',
		array(
			'label_for' => 'ca_ji_jira_username'
		)
	);
	// Adding field for Jira password
	add_settings_field(
		'ca_ji_jira_password',
		__( 'Password', 'crane-app-jira-integration' ),
		'ca_ji_field_jira_password',
		'ca_ji',
		'ca_ji_authentication',
		array(
			'label_for' => 'ca_ji_jira_password'
		)
	);
	/**
	 * Design
	 */
	// Registering design setting
	register_setting(
		'ca_ji',
		'ca_ji_design'
	);
	// Adding design section to settings page
	add_settings_section(
		'ca_ji_design',
		__( 'Design', 'crane-app-jira-integration' ),
		'ca_ji_design_section',
		'ca_ji'
	);
	// Adding field for Bootstrap
	add_settings_field(
		'ca_ji_bootstrap',
		__( 'Bootstrap', 'crane-app-jira-integration' ),
		'ca_ji_field_bootstrap',
		'ca_ji',
		'ca_ji_design',
		array()
	);
}
add_action( 'admin_init', 'ca_ji_settings_init' );

/**
 * Adding submenu to the Settings main menu
 */
function ca_ji_settings_menu() {
	// Adding submenu to the Settings main menu
	add_options_page(
		__( 'Jira Integration Settings', 'crane-app-jira-integration' ),
		__( 'Jira Integration', 'crane-app-jira-integration' ),
		'manage_options',
		'ca_ji',
		'ca_ji_settings_page'
	);
}
add_action( 'admin_menu', 'ca_ji_settings_menu' );

/**
 * Outputting content for settings page
 */
function ca_ji_settings_page() {
	// Outputting HTML
	echo '
			<div class="wrap">
				<h1>' . esc_html( get_admin_page_title() ) . '</h1>
				<form method="post" action="options.php">';
	// Outputting security fields
	settings_fields( 'ca_ji' );
	// Outputting HTML
	echo '
				<div class="notice notice-info">
					<p>' . __( 'The plugin <strong>Jira Integration</strong> from <a href="https://www.crane-app.de/" target="_blank">CRANE APP</a> does not fit your needs?', 'crane-app-jira-integration' ) . '</p>
					<p>' . __( 'We add more options for customization, we enhance usability or we do everything else making the plugin working as comfortable as possible.', 'crane-app-jira-integration' ) . '</p>
					<p>' . __( 'Just create a note on our <a href="https://www.crane-app.de/kontakt/" target="_blank">website</a>!', 'crane-app-jira-integration' ) . '</p>
				</div>
                <p>' . __( 'Use the shortcode <code>[crane_app_jira_integration]</code> to display Jira contents on your website. You can specify the output with five optional attributes:', 'crane-app-jira-integration' ) . '</p>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><code>jql</code></th>
							<td>
								' . __( 'Use <a href="https://confluence.atlassian.com/jirasoftwarecloud/advanced-searching-764478330.html" target="_blank">Jira Query Language (JQL)</a> to search for issues. The output will contain all found issues.', 'crane-app-jira-integration' ) . '
								<p class="description">' . esc_html( __( 'Default:', 'crane-app-jira-integration' ) ) . ' <code>assignee = currentUser() AND resolution = unresolved ORDER BY updated DESC</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><code>col_fields</code></th>
							<td>
								' . __( 'The issue fields which shall be displayed as columns. You can use both <a href="https://confluence.atlassian.com/adminjiraserver071/issue-fields-and-statuses-802592413.html" target="_blank">system</a> and custom fields.', 'crane-app-jira-integration' ) . '
								<p class="description">' . esc_html( __( 'Default:', 'crane-app-jira-integration' ) ) . ' <code>status,summary</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><code>col_labels</code></th>
							<td>
								' . __( 'The column labels according to the column fields. Separate multiple labels with a comma. Please keep in mind that the number of column labels is equal to the number of column fields.', 'crane-app-jira-integration' ) . '
								<p class="description">' . esc_html( __( 'Default:', 'crane-app-jira-integration' ) ) . ' <code>' . __( 'Status', 'crane-app-jira-integration' ) . ',' . __( 'Summary', 'crane-app-jira-integration' ) . '</code></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><code>more_fields</code></th>
							<td>
								' . __( 'The issue fields which shall be displayed as more information. You can use both <a href="https://confluence.atlassian.com/adminjiraserver071/issue-fields-and-statuses-802592413.html" target="_blank">system</a> and custom fields.', 'crane-app-jira-integration' ) . '
								<p class="description">' . esc_html( __( 'Default:', 'crane-app-jira-integration' ) ) . ' <code>summary,description</code></p>
							</td>
						</tr>
							<th scope="row"><code>more_labels</code></th>
							<td>
								' . __( 'The labels for more information according to the fields for more information. Separate multiple labels with a comma. Please keep in mind that the number of labels for more information is equal to the number of fields for more information.', 'crane-app-jira-integration' ) . '
								<p class="description">' . esc_html( __( 'Default:', 'crane-app-jira-integration' ) ) . ' <code>' . __( 'Summary', 'crane-app-jira-integration' ) . ',' . __( 'Description', 'crane-app-jira-integration' ) . '</code></p>
							</td>
					</tbody>
				</table>';
	// Outputting settings fields by section
	do_settings_sections( 'ca_ji' );
	// Outputting submit button
	submit_button();
	// Outputting HTML
	echo '
				</form>
			</div>';
}

/**
 * Outputting content for authentication section
 */
function ca_ji_authentication_section() {
	// Outputting HTML
	echo '';
}

/**
 * Outputting content for Jira base URL field
 *
 * @param $args
 */
function ca_ji_field_jira_base_url( $args ) {
	// Getting option
	$option = get_option( 'ca_ji_authentication' );
	// Outputting HTML
	echo '
			<input id="' . esc_attr( $args['label_for'] ) . '" type="url" name="ca_ji_authentication[jira_base_url]" value="' . esc_attr( isset( $option['jira_base_url'] ) ? $option['jira_base_url'] : '' ) . '" class="regular-text code" />
			<p class="description">' . __( 'Your full publically accessible Jira URL including protocol (https:// or http://) and trailing slash. For example <code>https://your-sitename.atlassian.net/</code> in case of Jira in the cloud or <code>https://www.your-domain.com/</code> if you host Jira yourself.', 'crane-app-jira-integration' ) . '</p>';
}

/**
 * Outputting content for Jira username field
 *
 * @param $args
 */
function ca_ji_field_jira_username( $args ) {
	// Getting option
	$option = get_option( 'ca_ji_authentication' );
	// Outputting HTML
	echo '
			<input id="' . esc_attr( $args['label_for'] ) . '" type="text" name="ca_ji_authentication[jira_username]" value="' . esc_attr( isset( $option['jira_username'] ) ? $option['jira_username'] : '' ) . '" class="regular-text ltr" />
			<p class="description">' . __( 'The username of your Jira account. Do not enter the email address.', 'crane-app-jira-integration' ) . '</p>';
}

/**
 * Outputting content for Jira password field
 *
 * @param $args
 */
function ca_ji_field_jira_password( $args ) {
	// Getting option
	$option = get_option( 'ca_ji_authentication' );
	// Outputting HTML
	echo '
			<input id="' . esc_attr( $args['label_for'] ) . '" type="password" name="ca_ji_authentication[jira_password]" value="' . esc_attr( isset( $option['jira_password'] ) ? $option['jira_password'] : '' ) . '" class="regular-text ltr" />
			<p class="description">' . __( 'The password of your Jira account.', 'crane-app-jira-integration' ) . '</p>';
}

/**
 * Outputting content for design section
 */
function ca_ji_design_section() {
	// Outputting HTML
	echo '';
}

/**
 * Outputting content for Bootstrap
 *
 * @param $args
 */
function ca_ji_field_bootstrap( $args ) {
	// Getting option
	$option = get_option( 'ca_ji_design' );
	// Outputting HTML
	echo '
			<fieldset>
				<legend class="screen-reader-text"><span>' . esc_html( __( 'Bootstrap', 'crane-app-jira-integration' ) ) . '</span></legend>
				<label for="ca_ji_bootstrap">
					<input id="ca_ji_bootstrap" type="checkbox" name="ca_ji_design[bootstrap]" value="1" ' . checked( true, isset( $option['bootstrap'] ), false ) . ' />
					' . esc_html( __( 'Use Bootstrap', 'crane-app-jira-integration' ) ) . '
				</label>
				<p class="description">' . __( 'If your theme includes Bootstrap the design for contents of Jira Integration will be consitent to your website. The framework Bootstrap 4 Alpha 6 is used.', 'crane-app-jira-integration' ) . '</p>
			</fieldset>';
}