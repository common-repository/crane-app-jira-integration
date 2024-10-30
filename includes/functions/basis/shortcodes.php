<?php
defined( 'ABSPATH' ) or die();

/**
 * Functions to manage shortcodes
 *
 * @author Kai Krannich
 * @version 1.1.0
 */

/*
 * Initializing shortcodes
 */
function ca_ji_shortcodes_init() {
	// Adding shortcode for default Jira integration
	function ca_ji_shortcode_default( $atts, $content, $tag ) {
		// Normalizing attribute keys
		$atts = array_change_key_case( ( array ) $atts, CASE_LOWER );
		// Overriding default attributes
		$atts = shortcode_atts(
			array(
				'jql' => 'assignee = currentUser() AND resolution = unresolved ORDER BY updated DESC',
				'col_fields' => 'status,summary',
				'col_labels' => __( 'Status', 'crane-app-jira-integration' ) . ',' . __( 'Summary', 'crane-app-jira-integration' ),
				'more_fields' => 'summary,description',
				'more_labels' => __( 'Summary', 'crane-app-jira-integration' ) . ',' . __( 'Description', 'crane-app-jira-integration' )
			),
			$atts,
			$tag
		);
		// Getting option
		$option_design = get_option( 'ca_ji_design' );
		/**
		 * Bootstrap
		 */
		$bootstrap_enabled = false;
		$bootstrap_error_alert = '';
		$bootstrap_table = '';
		$bootstrap_badge_default = '';
		$bootstrap_row = '';
		$bootstrap_col_1 = '';
		$bootstrap_col_2 = '';
		// Checking if Bootstrap is enabled
		if ( isset( $option_design['bootstrap'] ) ) {
			$bootstrap_enabled = true;
			$bootstrap_error_alert = 'class="alert alert-danger" role="alert"';
			$bootstrap_table = 'class="table table-striped table-responsive"';
			$bootstrap_badge_default = 'class="badge badge-default"';
			$bootstrap_row = 'class="row"';
			$bootstrap_col_1 = 'class="col-12 col-lg-4"';
			$bootstrap_col_2 = 'class="col-12 col-lg-8"';
		}
		// Cookie-based authenticating
		$session_cookie = ca_ji_jira_rest_cookie_auth();
		// Checking if errors occur
		if ( is_wp_error( $session_cookie ) ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Fields for columns
		$col_fields = array_map( 'trim', explode( ',', $atts['col_fields'] ) );
		// Labels for columns
		$col_labels = array_map( 'trim', explode( ',', $atts['col_labels'] ) );
		// Checking if data is unexpected
		if ( count( $col_fields ) !== count( $col_labels ) ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Fields for more information
		$more_fields = array_map( 'trim', explode( ',', $atts['more_fields'] ) );
		// Labels for more information
		$more_labels = array_map( 'trim', explode( ',', $atts['more_labels'] ) );
		// Checking if data is unexpected
		if ( count( $more_fields ) !== count( $more_labels ) ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Merging fields
		$fields = array_merge( $col_fields, $more_fields );
		// Getting option
		$option = get_option( 'ca_ji_authentication' );
		// Checking if data exists
		if ( ! isset( $option['jira_base_url'] ) ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Checking data types
		if ( ! is_string( $option['jira_base_url'] ) ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Jira base URL
		$jira_base_url = trim( $option['jira_base_url'] );
		// Checking if data is empty
		if ( empty( $jira_base_url ) ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Initializing a cURL session and returning a cURL handle
		$ch = curl_init();
		// Checking if errors occur
		if ( $ch === false ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Data to post
		$postfields = array(
			'jql'    => $atts['jql'],
			'fields' => $fields // todo
		);
		// Getting JSON representation
		$postfields = json_encode( $postfields );
		// Checking if errors occur
		if ( $postfields === false ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Options for cURL transfer
		$options = array(
			CURLOPT_HTTPHEADER     => array(
				'Content-type: application/json',
				'cookie: ' . $session_cookie->session->name . '=' . $session_cookie->session->value
			),
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $postfields,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_URL            => $jira_base_url . 'rest/api/2/search',
		);
		// Setting multiple options for cURL session
		$result = curl_setopt_array( $ch, $options );
		// Checking if errors occur
		if ( $result === false ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Executing the cURL session
		$result = curl_exec( $ch );
		// Checking if errors occur
		if ( $result === false ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// Closing the cURL session and deleting the cURL handle
		curl_close( $ch );
		// Decoding the JSON string
		$search = json_decode( $result );
		// Checking if errors occur
		if ( $search === null ) {
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		} elseif ( is_object( $search ) && isset( $search->errorMessages ) ) {
			// Logging errors
			if ( WP_DEBUG === true && WP_DEBUG_LOG === true ) {
				if ( is_array( $search->errorMessages ) ) {
					foreach ( $search->errorMessages as $errorMessage ) {
						error_log( $errorMessage );
					}
				}
			}
			// Outputting HTML
			return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
		}
		// HTML
		$html = '
					<table ' . $bootstrap_table . '>
						<thead>
							<tr>';
		// Iterating fields for columns
		foreach ( $col_labels as $col_label ) {
			// HTML
			$html .= '
								<th>' . esc_html( $col_label ) . '</th>';
		}
		// HTML
		$html .= '
								<th></th>
							</tr>
						</thead>
						<tbody>';
		// Iterating issues
		foreach ( $search->issues as &$issue ) {

			// var_dump( $issue ); die(); // todo

			// HTML
			$html .= '
							<tr>';
			// Iterating fields for columns
			foreach ( $col_fields as $col_field ) {
				switch ( $col_field ) {
					case 'status':
					case 'project':
					case 'issuetype':
					case 'priority':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_object( $issue->fields->$col_field ) && is_string( $issue->fields->$col_field->name ) ) {
							// Field value
							$field_value = esc_html( $issue->fields->$col_field->name );
						}
						break;
					case 'fixVersions':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_array( $issue->fields->$col_field ) ) {
							// Iterating items
							foreach ( $issue->fields->$col_field as $item ) {
								// Checking data types
								if ( is_object( $item ) && is_string( $item->name ) ) {
									if ( ! empty( $field_value ) ) {
										// Field value
										$field_value .= $bootstrap_enabled ? ' ' : '<br />';
									}
									// Field value
									$field_value .= '<span ' . $bootstrap_badge_default . '>' . esc_html( $item->name ) . '</span>';
								}
							}
						}
						break;
					case 'creator':
					case 'assignee':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_object( $issue->fields->$col_field ) && is_string( $issue->fields->$col_field->displayName ) ) {
							// Field value
							$field_value = '<span ' . $bootstrap_badge_default . '>' . esc_html( $issue->fields->$col_field->displayName ) . '</span>';
						}
						break;
					case 'created':
					case 'updated':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_string( $issue->fields->$col_field ) ) {
							// Getting Unix timestamp from datetime description
							$field_value = strtotime( $issue->fields->$col_field );
							// Checking if errors occur
							if ( $field_value === false ) {
								return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
							}
							// Field value
							$field_value = date( 'd.m.Y', $field_value );
							// Checking if errors occur
							if ( $field_value === false ) {
								return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
							}
						}
						break;
					default:
						// Field value
						$field_value = '';
						// Checking if data exists
						if ( isset( $issue->fields->$col_field ) ) {
							// Checking data types
							if ( is_string( $issue->fields->$col_field ) && ! filter_var( $issue->fields->$col_field, FILTER_VALIDATE_URL ) === false ) {
								// Field value
								$field_value = '<a href="' . esc_url( $issue->fields->$col_field ) . '" target="_blank" class="dashicons dashicons-admin-links"></a>';
							} elseif ( is_string( $issue->fields->$col_field ) ) {
								// Field value
								$field_value = esc_html( $issue->fields->$col_field );
							} elseif ( is_object( $issue->fields->$col_field ) && is_string( $issue->fields->$col_field->displayName ) ) {
								// Field value
								$field_value = '<span ' . $bootstrap_badge_default . '>' . esc_html( $issue->fields->$col_field->displayName ) . '</span>';
							}
						}
						break;
				}
				// HTML
				$html .= '
								<td>' . $field_value . '</td>';
			}
			// HTML
			$html .= '
								<td>';
			// Checking if Bootstrap is enabled
			if ( $bootstrap_enabled ) {
				// HTML
				$html .= '
									<a href="#" class="dashicons dashicons-info" data-toggle="modal" data-target="#' . esc_attr( $issue->key ) . '"></a>
									<div class="modal fade" id="' . esc_attr( $issue->key ) . '" tabindex="-1" role="dialog" aria-labelledby="' . esc_attr( $issue->key ) . '-modal-title" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="' . esc_attr( $issue->key ) . '-modal-title">' . esc_html( __( 'More information', 'crane-app-jira-integration' ) ) . '</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												</div>
												<div class="modal-body">
													<div class="container-fluid">';
			} else {
				// HTML
				$html .= '
									<a href="#" class="dashicons dashicons-info ca-ji-more-open" issue="' . esc_attr( $issue->key ) . '"></a>
									<div class="ca-ji-more-content" id="' . esc_attr( $issue->key ) . '" title="' . esc_attr( __( 'More information', 'crane-app-jira-integration' ) ) . '">';
			}
			// Iterating fields for more information
			for ( $i = 0; $i < count( $more_fields ); $i++ ) {
				switch ( $more_fields[$i] ) {
					case 'status':
					case 'project':
					case 'issuetype':
					case 'priority':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_object( $issue->fields->$more_fields[$i] ) && is_string( $issue->fields->$more_fields[$i]->name ) ) {
							// Field value
							$field_value = esc_html( $issue->fields->$more_fields[$i]->name );
						}
						break;
					case 'fixVersions':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_array( $issue->fields->$more_fields[$i] ) ) {
							// Iterating items
							foreach ( $issue->fields->$more_fields[$i] as $item ) {
								// Checking data types
								if ( is_object( $item ) && is_string( $item->name ) ) {
									if ( ! empty( $field_value ) ) {
										// Field value
										$field_value .= $bootstrap_enabled ? ' ' : '<br />';
									}
									// Field value
									$field_value .= '<span ' . $bootstrap_badge_default . '>' . esc_html( $item->name ) . '</span>';
								}
							}
						}
						break;
					case 'assignee':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_object( $issue->fields->$more_fields[$i] ) && is_string( $issue->fields->$more_fields[$i]->displayName ) ) {
							// Field value
							$field_value = '<span ' . $bootstrap_badge_default . '>' . esc_html( $issue->fields->$more_fields[$i]->displayName ) . '</span>';
						}
						break;
					case 'created':
					case 'updated':
						// Field value
						$field_value = '';
						// Checking data types
						if ( is_string( $issue->fields->$more_fields[$i] ) ) {
							// Getting Unix timestamp from datetime description
							$field_value = strtotime( $issue->fields->$more_fields[$i] );
							// Checking if errors occur
							if ( $field_value === false ) {
								return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
							}
							// Field value
							$field_value = date( 'd.m.Y', $field_value );
							// Checking if errors occur
							if ( $field_value === false ) {
								return '<div ' . $bootstrap_error_alert . '><strong>' . esc_html( __( 'Oops!', 'crane-app-jira-integration' ) ) . '</strong> ' . esc_html( __( 'An error has occurred.', 'crane-app-jira-integration' ) ) . '</div>';
							}
						}
						break;
					default:
						// Field value
						$field_value = '';
						// Checking if data exists
						if ( isset( $issue->fields->$more_fields[$i] ) ) {
							// Checking data types
							if ( is_string( $issue->fields->$more_fields[ $i ] ) && ! filter_var( $issue->fields->$more_fields[ $i ], FILTER_VALIDATE_URL ) === false ) {
								// Field value
								$field_value = '<a href="' . esc_url( $issue->fields->$more_fields[ $i ] ) . '" target="_blank">' . esc_html( $issue->fields->$more_fields[ $i ] ) . '</a>';
							} elseif ( is_string( $issue->fields->$more_fields[ $i ] ) ) {
								// Field value
								$field_value = esc_html( $issue->fields->$more_fields[ $i ] );
							} elseif ( is_object( $issue->fields->$more_fields[ $i ] ) && is_string( $issue->fields->$more_fields[ $i ]->displayName ) ) {
								// Field value
								$field_value = '<span ' . $bootstrap_badge_default . '>' . esc_html( $issue->fields->$more_fields[ $i ]->displayName ) . '</span>';
							}
						}
						break;
				}
				// HTML
				$html .= '
														<div ' . $bootstrap_row . '>
															<div ' . $bootstrap_col_1 . '><strong>' . esc_html( $more_labels[$i] ) . '</strong></div>
															<div ' . $bootstrap_col_2 . '>' . $field_value . '</div>
														</div>';
			}
			// Checking if Bootstrap is enabled
			if ( $bootstrap_enabled ) {
				// HTML
				$html .= '
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">' . esc_html( __( 'Close', 'crane-app-jira-integration' ) ) . '</button>
												</div>
											</div>
										</div>
									</div>';
			} else {
				// HTML
				$html .= '
									</div>';
			}
			// HTML
			$html .= '			
								</td>
							</tr>';
		}
		// HTML
		$html .= '
						</tbody>
					</table>';

		// Outputting HTML
		return $html;
	}
	add_shortcode( 'crane_app_jira_integration', 'ca_ji_shortcode_default' );
}
add_action( 'init', 'ca_ji_shortcodes_init' );