<?php
/*
Plugin Name: WP-Pay-Pi
Description: Plugin pour intégrer le paiement avec la cryptomonnaie Pi Network.
Version: 1.0.0
Author: Dujardin Fabrice HELIUM NC
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Inclure Composer Autoload
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Inclure les fichiers principaux
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-pay-pi.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-pay-pi-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-pay-pi-gateway.php';

// Inclure les fonctions
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

// Initialisation du plugin
function wp_pay_pi_init() {
    $wp_pay_pi = new WP_Pay_Pi();
    $wp_pay_pi_admin = new WP_Pay_Pi_Admin();
    $wp_pay_pi_gateway = new WP_Pay_Pi_Gateway();
}
add_action( 'plugins_loaded', 'wp_pay_pi_init' );

// Enqueue des styles
function wp_pay_pi_enqueue_styles() {
    wp_enqueue_style(
        'wp-pay-pi-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/wp-pay-pi.css',
        array(),
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'wp_pay_pi_enqueue_styles' );
