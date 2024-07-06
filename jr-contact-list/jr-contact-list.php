<?php
/*
Plugin Name: Contact List Plugin
Description: Plugin for managing contact sign-ups with taglist component for hobbies.
Version: 1.0
Author: Jijanur Rahman
*/

// Enqueue scripts and styles for frontend and admin
function clp_enqueue_scripts() {
    // Enqueue block script
    wp_enqueue_script(
        'contact-list-block',
        plugins_url('blocks/contact-list-block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'blocks/contact-list-block.js')
    );

    // Enqueue CSS
    wp_enqueue_style(
        'contact-list-css',
        plugins_url('css/contact-list.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'css/contact-list.css')
    );
}
add_action('enqueue_block_editor_assets', 'clp_enqueue_scripts');

// Register admin menu
function clp_register_admin_menu() {
    add_menu_page(
        'Contact List Plugin',
        'Contact List',
        'manage_options',
        'contact-list-admin',
        'clp_admin_page_content',
        'dashicons-businessman'
    );
}
add_action('admin_menu', 'clp_register_admin_menu');

// Render admin page content
function clp_admin_page_content() {
    // Include admin page template
    include(plugin_dir_path(__FILE__) . 'templates/admin-page.php');
}

// Register sign-up page template
function clp_register_sign_up_page($templates) {
    $templates['signup-page.php'] = 'Sign Up Page';
    return $templates;
}
add_filter('theme_page_templates', 'clp_register_sign_up_page');

// Load custom templates
function clp_load_custom_template($template) {
    global $post;

    if (!isset($post)) {
        return $template;
    }

    if ('signup-page.php' === get_post_meta($post->ID, '_wp_page_template', true)) {
        $template = plugin_dir_path(__FILE__) . 'templates/signup-page.php';
    }

    return $template;
}
add_filter('template_include', 'clp_load_custom_template');

// Create custom table on plugin activation
function clp_create_table_on_activation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_list';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            address varchar(255) NOT NULL,
            phone varchar(20) NOT NULL,
            email varchar(100) NOT NULL,
            hobbies text NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'clp_create_table_on_activation');

// Remove custom table on plugin deactivation
function clp_remove_table_on_deactivation() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_list';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
register_deactivation_hook(__FILE__, 'clp_remove_table_on_deactivation');


function clp_sign_up_form_shortcode() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'templates/signup-page.php');
    return ob_get_clean();
}
add_shortcode('contact_list_sign_up_form', 'clp_sign_up_form_shortcode');
