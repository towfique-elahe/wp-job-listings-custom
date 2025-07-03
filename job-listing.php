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
                        echo '<select name="' . esc_attr($key) . '" class="filter-select" id="' . esc_attr($key) . '">';
                        echo '<option value="">' . esc_html('Select ' . $custom_headings[$key]) . '</option>';
                        foreach ($terms as $term) {
                            echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                        }
                        echo '</select>';
                        echo '</div>';
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
                                <?php
                                    $settings = get_option('wpjlc_settings');
                                    $default_per_page = $settings['jobs_per_page'] ?? '9';
                                    $per_page_options = [6, 9, 12, 15, 18];
                                ?>
                                <select id="jobs_per_page">
                                    <?php foreach ($per_page_options as $val): ?>
                                    <option value="<?php echo esc_attr($val); ?>"
                                        <?php selected($val, $default_per_page); ?>>
                                        <?php echo esc_html($val); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="sort-by">
                                Sort by:
                                <select id="sort_order">
                                    <option value="desc">Newest Jobs</option>
                                    <option value="asc">Oldest Jobs</option>
                                </select>
                            </div>
                            <div class="view-toggle">
                                <span class="view-icon grid active" data-view="grid">
                                    <ion-icon name="grid-outline"></ion-icon>
                                </span>
                                <span class="view-icon list" data-view="list">
                                    <ion-icon name="list-outline"></ion-icon>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Job Listings will be inserted here -->
                <div id="job-results-grid" class="jobs grid-view"></div>
                <div id="job-results-list" class="jobs list-view" style="display: none;"></div>
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