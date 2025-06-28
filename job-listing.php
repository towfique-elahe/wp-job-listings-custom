<?php

function render_custom_job_listing() {
    ob_start();

    // Fetch filter options dynamically
    $job_categories = get_terms(['taxonomy' => 'job-category', 'hide_empty' => false]);
    $job_types = get_terms(['taxonomy' => 'job-type', 'hide_empty' => false]);
    $job_locations = get_terms(['taxonomy' => 'job-location', 'hide_empty' => false]);

    ?>
<div id="jobListing" class="custom-job-listing">
    <div class="container">
        <div class="col sidebar">
            <a href="javascript:void(0)" class="close-sidebar" style="display:none;">×</a>
            <div class="sidebar-header">
                <h3 class="sidebar-heading">Filters</h3>
                <a href="javascript:void()" class="reset-button">Reset</a>
            </div>
            <div class="filter-group">
                <div class="search">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" name="search" id="search" placeholder="Search jobs...">
                </div>
            </div>
            <?php
                    // Add job categories, types, and locations to the filter group
                    $filters = [
                        'job-category' => $job_categories,
                        'job-type' => $job_types,
                        'job-location' => $job_locations,
                    ];

                    $custom_headings = [
                        'job-category' => 'Job Category',
                        'job-type' => 'Job Type',
                        'job-location' => 'Job Location',
                    ];

                    foreach ($filters as $key => $terms) {
                        echo '<div class="filter-group">';
                        echo '<h4 class="filter-heading">' . esc_html($custom_headings[$key]) . '</h4>';
                        echo '<div class="filter-items">';
                        foreach ($terms as $term) {
                            echo '<div class="filter-item">';
                            echo '<input type="checkbox" class="filter-checkbox" name="' . esc_attr($key) . '[]" value="' . esc_attr($term->slug) . '" id="' . esc_attr($term->slug) . '">';
                            echo '<label for="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</label>';
                            echo '</div>';
                        }
                        echo '</div></div>';
                    }
                ?>
        </div>

        <!-- Jobs Content -->
        <div class="col jobs-content">
            <div class="content">
                <!-- header -->
                <div class="row content-header">
                    <div class="col">
                        <div class="mobile-filter-toggle">
                            <ion-icon name="options-outline"></ion-icon> <span>Filters</span>
                        </div>
                        <p class="showing-text"></p>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="job-count">
                                Show:
                                <select id="jobs_per_page">
                                    <option value="6">6</option>
                                    <option value="9">9</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="sort-by">
                                Sort by:
                                <select id="sort_order">
                                    <option value="desc">Newest Jobs</option>
                                    <option value="asc">Oldest Jobs</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Job Listings will be inserted here -->
                <div id="job-results" class="jobs"></div>
            </div>
        </div>
    </div>
</div>
<?php
    return ob_get_clean();
}

// Register the shortcode
function register_custom_job_listing_shortcode() {
    add_shortcode( 'custom_job_listing', 'render_custom_job_listing' );
}
add_action( 'init', 'register_custom_job_listing_shortcode' );