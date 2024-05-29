<?php
// Enqueue scripts and styles
function enqueue_custom_scripts() {
    wp_enqueue_style('news-styles', get_template_directory_uri() . '/css/news-styles.css');
    wp_enqueue_script('news-scripts', get_template_directory_uri() . '/js/news-scripts.js', array('jquery'), null, true);

    wp_localize_script('news-scripts', 'my_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
?>
