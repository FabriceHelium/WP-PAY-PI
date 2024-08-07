<?php

use ScssPhp\ScssPhp\Compiler;

/**
 * Récupère une option du plugin.
 *
 * @param string $option_name Le nom de l'option.
 * @return mixed La valeur de l'option.
 */
function wp_pay_pi_get_option( $option_name ) {
    return get_option( 'wp_pay_pi_' . $option_name );
}

/**
 * Compile SCSS en CSS.
 *
 * @return void
 */
function wp_pay_pi_compile_scss() {
    try {
        $scss = new Compiler();

        // Chemins des fichiers SCSS et CSS
        $scss_file = plugin_dir_path( __FILE__ ) . '../assets/scss/style.scss';
        $css_file = plugin_dir_path( __FILE__ ) . '../assets/css/wp-pay-pi.css';

        if ( ! file_exists( $scss_file ) ) {
            throw new Exception( 'Le fichier SCSS est introuvable.' );
        }

        // Lire le contenu SCSS
        $scss_content = file_get_contents( $scss_file );

        // Compiler SCSS en CSS
        $css_content = $scss->compile( $scss_content );

        // Écrire le contenu CSS dans le fichier
        if ( file_put_contents( $css_file, $css_content ) === false ) {
            throw new Exception( 'Erreur lors de l\'écriture du fichier CSS.' );
        }
    } catch ( Exception $e ) {
        error_log( 'Erreur de compilation SCSS: ' . $e->getMessage() );
    }
}

/**
 * Hook pour compiler SCSS lors de l'activation du plugin.
 *
 * @return void
 */
function wp_pay_pi_activate() {
    wp_pay_pi_compile_scss();
}
register_activation_hook( __FILE__, 'wp_pay_pi_activate' );

/**
 * Hook pour compiler SCSS lors de la mise à jour du plugin.
 *
 * @param object $upgrader_object L'objet Upgrader.
 * @param array  $options Les options de mise à jour.
 * @return void
 */
function wp_pay_pi_update( $upgrader_object, $options ) {
    if ( isset( $options['action'] ) && $options['action'] === 'update' ) {
        wp_pay_pi_compile_scss();
    }
}
add_action( 'upgrader_process_complete', 'wp_pay_pi_update', 10, 2 );
