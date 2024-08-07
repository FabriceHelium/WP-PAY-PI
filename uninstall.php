<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Exemple de code pour supprimer les options du plugin
delete_option( 'wp_pay_pi_options' );
