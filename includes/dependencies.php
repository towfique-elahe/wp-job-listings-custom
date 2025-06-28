<?php

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include plugin functions (for is_plugin_active)
if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if required plugin is active — if not, stop everything
if ( ! is_plugin_active( 'wp-job-openings/wp-job-openings.php' ) ) {
    add_action( 'admin_notices', 'wp_job_listings_custom_dependency_notice' );
    return; // Prevent any plugin code from executing
}

// Block plugin activation if dependency is not active
register_activation_hook( __FILE__, 'wp_job_listings_custom_on_activate' );

function wp_job_listings_custom_on_activate() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( ! is_plugin_active( 'wp-job-openings/wp-job-openings.php' ) ) {
        wp_die(
            esc_html__( 'Custom Job Listing requires the WP Job Openings plugin to be activated before you can use this plugin.', 'wp-job-listings-custom' ),
            esc_html__( 'Plugin Dependency Check Failed', 'wp-job-listings-custom' ),
            array( 'back_link' => true )
        );
    }
}

// Admin notice when WP Job Openings is not active
function wp_job_listings_custom_dependency_notice() {
    $plugin_slug = 'wp-job-openings';
    $install_url = wp_nonce_url(
        self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ),
        'install-plugin_' . $plugin_slug
    );

    echo '<div class="notice notice-error"><p><strong>' .
        esc_html__( 'Custom Job Listing requires the WP Job Openings plugin to be installed and activated.', 'wp-job-listings-custom' ) .
        '</strong><br><a href="' . esc_url( $install_url ) . '">' .
        esc_html__( 'Click here to install WP Job Openings.', 'wp-job-listings-custom' ) .
        '</a></p></div>';
}