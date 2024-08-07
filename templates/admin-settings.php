<div class="wrap">
    <h1>WP Pay Pi Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'wp_pay_pi_options_group' );
        do_settings_sections( 'wp-pay-pi' );
        submit_button();
        ?>
    </form>
</div>
