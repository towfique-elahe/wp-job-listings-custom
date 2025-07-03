<?php

/**
 * Plugin Name: Custom Job Listing
 * Plugin URI: https://towfique-elahe.framer.website/custom-job-listing
 * Description: Extends the WP Job Openings plugin by adding custom job listing views, styles, and shortcode functionality. Enhances job board UI with modern components like Ionicons and AJAX filtering.
 * Version: 1.0.4
 * Author: Orbit570
 * Author URI: https://orbit570.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-job-listings-custom
 * Tested up to: 6.8
 */

// global plugin URL constant
define( 'WPJLC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// include files
include_once plugin_dir_path( __FILE__ ) . 'includes/dependencies.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/assets.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/ajax-handler.php';
include_once plugin_dir_path( __FILE__ ) . 'job-listing.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';