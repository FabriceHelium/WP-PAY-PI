<?php
class WP_Pay_Pi {
    public function __construct() {
        // Initialisation du plugin
        add_action( 'init', array( $this, 'init_plugin' ) );
    }

    public function init_plugin() {
        // Code pour initialiser le plugin
    }
}
