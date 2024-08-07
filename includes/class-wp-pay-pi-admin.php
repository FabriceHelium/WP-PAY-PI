<?php
class WP_Pay_Pi_Admin {
    public function __construct() {
        // Ajout de la page de paramètres dans le menu d'administration
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
    }

    public function add_plugin_admin_menu() {
        add_options_page(
            'WP Pay Pi Settings',
            'WP Pay Pi',
            'manage_options',
            'wp-pay-pi',
            array( $this, 'display_plugin_admin_page' )
        );
    }

    public function display_plugin_admin_page() {
        include plugin_dir_path( __FILE__ ) . '../templates/admin-settings.php';
    }
}
