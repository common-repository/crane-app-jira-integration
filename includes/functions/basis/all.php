<?php
defined( 'ABSPATH' ) or die();

/**
 * Inclusion of all basis functions
 *
 * @author Kai Krannich
 * @version 1.0
 */

// Including functions to manage textdomain
require_once( 'textdomain.php' );
// Including functions to manage shortcodes
require_once( 'shortcodes.php' );
// Including functions to manage scripts
require_once( 'enqueue-scripts.php' );
// Including functions to manage settings
require_once( 'settings.php' );
// Including functions to manage plugin page
require_once( 'plugin-page.php' );