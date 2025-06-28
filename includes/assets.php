<?php

// Enqueue scripts and styles
function wp_job_listings_custom_enqueue_assets() {
    if ( is_singular() && has_shortcode( get_post()->post_content, 'custom_job_listing' ) ) {
        wp_enqueue_style( 'job-listing-styles', WPJLC_PLUGIN_URL . 'assets/job-listing.css' );
        wp_enqueue_script( 'job-listing-ajax', WPJLC_PLUGIN_URL . 'assets/job-listing.js', array( 'jquery' ), null, true );

        wp_localize_script( 'job-listing-ajax', 'job_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'job_filter_nonce' )
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'wp_job_listings_custom_enqueue_assets' );

// Add Ionicons CDN scripts to the front-end
function wp_job_listings_custom_add_ionicons_cdn() {
    if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'wp-job-openings/wp-job-openings.php' ) ) {
        printf(
            '<script type="module" src="%s"></script>' . "\n" .
            '<script nomodule src="%s"></script>' . "\n",
            esc_url( 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js' ),
            esc_url( 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js' )
        );
    }
}