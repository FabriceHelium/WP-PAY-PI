<?php
class WP_Pay_Pi_Admin {
    public function __construct() {
        // Ajout de la page de paramètres dans le menu d'administration
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_plugin_admin_menu() {
        add_options_page(
            'WP Pay Pi Settings',
            'WP Pay Pi',
            'manage_options',
            'wp-pay-pi',
            array($this, 'display_plugin_admin_page')
        );
    }

    public function register_settings() {
        register_setting('wp_pay_pi_options_group', 'wp_pay_pi_api_key');
        register_setting('wp_pay_pi_options_group', 'wp_pay_pi_conversion_rate');
    }

    public function display_plugin_admin_page() {
        include plugin_dir_path(__FILE__) . '../templates/admin-settings.php';
    }
}