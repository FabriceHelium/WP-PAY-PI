<?php
class WP_Pay_Pi {
    private $cache_key = 'wp_pay_pi_exchange_rate';
    private $cache_duration = 900; // 15 minutes en secondes

    public function __construct() {
        add_action('init', array($this, 'init_plugin'));
        add_filter('woocommerce_get_price_html', array($this, 'display_pi_price'), 10, 2);
        add_filter('woocommerce_cart_item_price', array($this, 'display_pi_price'), 10, 2);
        add_filter('woocommerce_cart_item_subtotal', array($this, 'display_pi_price'), 10, 2);
        add_filter('woocommerce_get_order_item_totals', array($this, 'modify_order_totals'), 10, 2);
        
        // Ajouter un cron pour mettre à jour le taux
        add_action('wp_pay_pi_update_rate', array($this, 'update_exchange_rate'));
        if (!wp_next_scheduled('wp_pay_pi_update_rate')) {
            wp_schedule_event(time(), 'fifteen_minutes', 'wp_pay_pi_update_rate');
        }
    }

    public function init_plugin() {
        // Ajouter un intervalle de 15 minutes
        add_filter('cron_schedules', function($schedules) {
            $schedules['fifteen_minutes'] = array(
                'interval' => 900,
                'display'  => 'Every 15 minutes'
            );
            return $schedules;
        });
    }

    /**
     * Obtient le taux de change depuis CoinGecko
     */
    public function get_exchange_rate() {
        $cached_rate = get_transient($this->cache_key);
        if ($cached_rate !== false) {
            return $cached_rate;
        }

        $rate = $this->update_exchange_rate();
        return $rate;
    }

    /**
     * Met à jour le taux de change
     */
    public function update_exchange_rate() {
        $default_rate = get_option('wp_pay_pi_conversion_rate', 314.15);
        
        try {
            $response = wp_remote_get('https://api.coingecko.com/api/v3/simple/price?ids=pi-network&vs_currencies=usd');
            
            if (is_wp_error($response)) {
                return $default_rate;
            }

            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['pi-network']['usd'])) {
                $pi_usd_rate = 1 / $data['pi-network']['usd']; // Convertir en PI/USD
                set_transient($this->cache_key, $pi_usd_rate, $this->cache_duration);
                update_option('wp_pay_pi_conversion_rate', $pi_usd_rate);
                return $pi_usd_rate;
            }
        } catch (Exception $e) {
            error_log('WP Pay Pi - Erreur de récupération du taux: ' . $e->getMessage());
        }

        return $default_rate;
    }

    /**
     * Convertit un prix en Pi
     */
    private function convert_to_pi($price) {
        $rate = $this->get_exchange_rate();
        return $price * $rate;
    }

    /**
     * Affiche le prix en Pi
     */
    public function display_pi_price($price_html, $product) {
        if (!$product) {
            return $price_html;
        }

        $price = 0;
        
        if (method_exists($product, 'get_price')) {
            $price = $product->get_price();
        } elseif (is_numeric($product)) {
            $price = $product;
        }

        if ($price) {
            $pi_price = $this->convert_to_pi($price);
            $price_html .= ' <span class="pi-price">(' . number_format($pi_price, 2) . ' π)</span>';
        }

        return $price_html;
    }

    /**
     * Modifie les totaux de la commande pour afficher les prix en Pi
     */
    public function modify_order_totals($total_rows, $order) {
        foreach ($total_rows as $key => $row) {
            if (isset($row['value']) && is_numeric(strip_tags($row['value']))) {
                $price = strip_tags($row['value']);
                $pi_price = $this->convert_to_pi($price);
                $total_rows[$key]['value'] .= ' (' . number_format($pi_price, 2) . ' π)';
            }
        }
        return $total_rows;
    }
}