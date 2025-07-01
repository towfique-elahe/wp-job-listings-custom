<?php

function ajax_filter_jobs() {
    check_ajax_referer('job_filter_nonce', 'nonce');

    $paged          = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $search         = sanitize_text_field($_POST['search']);
    $posts_per_page = intval($_POST['posts_per_page']) ?: 3;
    $order          = sanitize_text_field($_POST['order']) === 'asc' ? 'ASC' : 'DESC';

    $tax_query = [];

    $taxonomies = ['job-category', 'job-type', 'job-location'];
    foreach ($taxonomies as $tax) {
        if (!empty($_POST[$tax])) {
            $terms = $_POST[$tax];
            if (!is_array($terms)) {
                $terms = [$terms];
            }
            $tax_query[] = [
                'taxonomy' => $tax,
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $terms),
            ];
        }
    }

    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }

    $args = [
        'post_type'      => 'awsm_job_openings',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'post_status'    => 'publish',
        's'              => $search,
        'order'          => $order,
    ];

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="jobs">';
        while ($query->have_posts()) {
            $query->the_post();
            ?>
<div class="job">
    <div class="head">
        <?php
    if (has_post_thumbnail()) {
        the_post_thumbnail('medium', ['class' => 'featured-image']);
    } else {
        $fallback_url = esc_url(WPJLC_PLUGIN_URL . 'assets/media/image-placeholder.png');
        echo '<img src="' . $fallback_url . '" class="featured-image" alt="Placeholder Image">';
    }
    ?>
    </div>
    <div class="body">
        <h3><a href="<?php the_permalink(); ?>" class="title">
                <?php the_title(); ?>
            </a></h3>

        <?php
        
    // Get taxonomies
    $jobCategory = get_the_terms(get_the_ID(), 'job-category');
    $jobType = get_the_terms(get_the_ID(), 'job-type');
    $jobLocation = get_the_terms(get_the_ID(), 'job-location');

    // Specs display
    echo '<div class="specs">';
    if ($jobCategory && !is_wp_error($jobCategory)) {
        echo '<p class="spec"><strong>Job Category:</strong> ' . esc_html(join(', ', wp_list_pluck($jobCategory, 'name'))) . '</p>';
    }
    if ($jobType && !is_wp_error($jobType)) {
        echo '<p class="spec"><strong>Job Type:</strong> ' . esc_html(join(', ', wp_list_pluck($jobType, 'name'))) . '</p>';
    }
    if ($jobLocation && !is_wp_error($jobLocation)) {
        echo '<p class="spec"><strong>Job Location:</strong> ' . esc_html(join(', ', wp_list_pluck($jobLocation, 'name'))) . '</p>';
    }
    echo '</div>';
    ?>
    </div>
</div>
<?php
        }
        echo '</div>';

        // Pagination
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a href="#" class="page ' . ($i == $paged ? 'active' : '') . '" data-page="' . $i . '">' . $i . '</a>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="jobs-msg">No jobs available.</p>';
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_jobs', 'ajax_filter_jobs');
add_action('wp_ajax_nopriv_filter_jobs', 'ajax_filter_jobs');