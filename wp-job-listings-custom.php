<?php
/**
 * Plugin Name: WP Job Listings Custom View
 * Description: Custom job listing views for WP Job Openings plugin.
 * Version: 1.0.0
 * Author: Towfique Elahe
 * Text Domain: wp-job-listings-custom
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if WP Job Openings is active and prevent plugin activation if not
function wp_job_listings_custom_check_plugin_dependencies() {
    if ( ! is_plugin_active( 'wp-job-openings/wp-job-openings.php' ) ) {
        // Show a dependency error message if WP Job Openings is not active
        add_action( 'admin_notices', 'wp_job_listings_custom_dependency_error' );
        // Deactivate the plugin to prevent usage without the dependency
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
}
add_action( 'admin_init', 'wp_job_listings_custom_check_plugin_dependencies' );

// Display error message if WP Job Openings is not activated
function wp_job_listings_custom_dependency_error() {
    echo '<div class="error"><p><strong>' . esc_html__( 'WP Job Listings Custom View requires the WP Job Openings plugin to be installed and activated.', 'wp-job-listings-custom' ) . '</strong></p></div>';
}

// Enqueue scripts and styles only if WP Job Openings is active
function wp_job_listings_custom_enqueue_assets() {
    // Ensure WP Job Openings is active before proceeding
    if ( is_plugin_active( 'wp-job-openings/wp-job-openings.php' ) ) {
        wp_enqueue_style( 'job-listing-styles', plugin_dir_url( __FILE__ ) . 'job-listing.css' );
        wp_enqueue_script( 'job-listing-ajax', plugin_dir_url( __FILE__ ) . 'job-listing.js', array( 'jquery' ), null, true );
        wp_localize_script( 'job-listing-ajax', 'job_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'job_filter_nonce' )
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'wp_job_listings_custom_enqueue_assets' );

// Include the job listings shortcode
include_once plugin_dir_path( __FILE__ ) . 'job-listings.php';

// Register the shortcode
function register_custom_job_listing_shortcode() {
    add_shortcode( 'custom_job_listing', 'render_custom_job_listing' );
}
add_action( 'init', 'register_custom_job_listing_shortcode' );