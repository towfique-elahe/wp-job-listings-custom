<?php
function wpjlc_register_settings() {
    register_setting('wpjlc_settings_group', 'wpjlc_settings');

    add_settings_section('wpjlc_general_section', 'Job Listing Display Settings', null, 'wpjlc-settings');

    add_settings_field('default_view', 'Default View (Grid/List)', 'wpjlc_default_view_cb', 'wpjlc-settings', 'wpjlc_general_section');
    add_settings_field('jobs_per_page', 'Jobs Per Page', 'wpjlc_jobs_per_page_cb', 'wpjlc-settings', 'wpjlc_general_section');
    add_settings_field('title_typography', 'Job Title Typography', 'wpjlc_title_typography_cb', 'wpjlc-settings', 'wpjlc_general_section');
    add_settings_field('term_typography', 'Job Term Typography', 'wpjlc_term_typography_cb', 'wpjlc-settings', 'wpjlc_general_section');
    add_settings_field('global_styles', 'Global Font & Colors', 'wpjlc_global_styles_cb', 'wpjlc-settings', 'wpjlc_general_section');
}

add_action('admin_init', 'wpjlc_register_settings');

function wpjlc_settings_menu() {
    add_menu_page('Job Listings Settings', 'Job Listing', 'manage_options', 'wpjlc-settings', 'wpjlc_settings_page');
}
add_action('admin_menu', 'wpjlc_settings_menu');

function wpjlc_settings_page() {
    ?>
<div class="wrap">
    <h1>Custom Job Listing Settings</h1>
    <form method="post" action="options.php">
        <?php
                settings_fields('wpjlc_settings_group');
                do_settings_sections('wpjlc-settings');
                submit_button();
            ?>
    </form>
</div>
<?php
}

// Callbacks
function wpjlc_default_view_cb() {
    $options = get_option('wpjlc_settings');
    $value = $options['default_view'] ?? 'grid';
    ?>
<select name="wpjlc_settings[default_view]">
    <option value="grid" <?php selected($value, 'grid'); ?>>Grid</option>
    <option value="list" <?php selected($value, 'list'); ?>>List</option>
</select>
<?php
}

function wpjlc_jobs_per_page_cb() {
    $options = get_option('wpjlc_settings');
    $value = $options['jobs_per_page'] ?? '8';
    ?>
<select name="wpjlc_settings[jobs_per_page]">
    <option value="6" <?php selected($value, '6'); ?>>6</option>
    <option value="9" <?php selected($value, '9'); ?>>9</option>
    <option value="12" <?php selected($value, '12'); ?>>12</option>
    <option value="15" <?php selected($value, '15'); ?>>15</option>
    <option value="18" <?php selected($value, '18'); ?>>18</option>
</select>
<?php
}

function wpjlc_title_typography_cb() {
    $options = get_option('wpjlc_settings');
    ?>
<input type="text" name="wpjlc_settings[title_font]" placeholder='e.g. "Open Sans"'
    value="<?php echo esc_attr($options['title_font'] ?? ''); ?>" />
<input type="number" name="wpjlc_settings[title_size]" placeholder="Font Size (px)"
    value="<?php echo esc_attr($options['title_size'] ?? ''); ?>" />
<input type="number" name="wpjlc_settings[title_weight]" placeholder="Font Weight"
    value="<?php echo esc_attr($options['title_weight'] ?? ''); ?>" />
<?php
}

function wpjlc_term_typography_cb() {
    $options = get_option('wpjlc_settings');
    ?>
<input type="text" name="wpjlc_settings[term_font]" placeholder='e.g. "Open Sans"'
    value="<?php echo esc_attr($options['term_font'] ?? ''); ?>" />
<input type="number" name="wpjlc_settings[term_size]" placeholder="Font Size (px)"
    value="<?php echo esc_attr($options['term_size'] ?? ''); ?>" />
<input type="number" name="wpjlc_settings[term_weight]" placeholder="Font Weight"
    value="<?php echo esc_attr($options['term_weight'] ?? ''); ?>" />
<?php
}

function wpjlc_global_styles_cb() {
    $options = get_option('wpjlc_settings');
    ?>
<label>Font Family:</label><br>
<input type="text" name="wpjlc_settings[global_font]" value="<?php echo esc_attr($options['global_font'] ?? ''); ?>"
    placeholder='e.g. "Open Sans"' /><br><br>

<label>Accent Color:</label><br>
<input type="color" name="wpjlc_settings[accent_color]"
    value="<?php echo esc_attr($options['accent_color'] ?? '#154344'); ?>" /><br><br>

<label>Text Color:</label><br>
<input type="color" name="wpjlc_settings[text_color]"
    value="<?php echo esc_attr($options['text_color'] ?? '#585858'); ?>" />
<?php
}

// injecting dynamic CSS in wp_head
function wpjlc_dynamic_styles() {
    $opts = get_option('wpjlc_settings');
    ?>
<style>
#jobListing .job .title {
    font-family: <?php echo esc_attr($opts['title_font'] ?? 'inherit');
    ?>;
    font-size: <?php echo esc_attr($opts['title_size'] ?? 'inherit');
    ?>px;
    font-weight: <?php echo esc_attr($opts['title_weight'] ?? 'inherit');
    ?>;
}

#jobListing .job .specs {
    font-family: <?php echo esc_attr($opts['term_font'] ?? 'inherit');
    ?>;
    font-size: <?php echo esc_attr($opts['term_size'] ?? 'inherit');
    ?>px;
    font-weight: <?php echo esc_attr($opts['term_weight'] ?? 'inherit');
    ?>;
}

:root {
    --accent-color: <?php echo esc_attr($opts['accent_color'] ?? '#154344');
    ?>;
    --text-color: <?php echo esc_attr($opts['text_color'] ?? '#585858');
    ?>;
    --accent-font-family: <?php echo esc_attr($opts['global_font'] ?? 'inherit');
    ?>;
}
</style>
<?php
}
add_action('wp_head', 'wpjlc_dynamic_styles');