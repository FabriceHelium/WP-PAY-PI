<div class="wrap">
    <h1>WP Pay Pi Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('wp_pay_pi_options_group');
        do_settings_sections('wp-pay-pi');
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="wp_pay_pi_api_key">API Key Pi Network</label>
                </th>
                <td>
                    <input type="text" id="wp_pay_pi_api_key" name="wp_pay_pi_api_key" 
                           value="<?php echo esc_attr(get_option('wp_pay_pi_api_key')); ?>" class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="wp_pay_pi_conversion_rate">Taux de conversion (1 USD = X Pi)</label>
                </th>
                <td>
                    <input type="number" step="0.000001" id="wp_pay_pi_conversion_rate" name="wp_pay_pi_conversion_rate" 
                           value="<?php echo esc_attr(get_option('wp_pay_pi_conversion_rate', '314.15')); ?>" class="regular-text">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>