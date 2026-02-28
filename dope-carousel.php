<?php
/**
 * Plugin Name: Dope Carousel for Elementor
 * Description: Elementor carousel widget with slider, single row, double row, and ticker/fade effects.
 * Version: 1.2.2
 * Author: DopeCarousel
 * Text Domain: dope-carousel
 * Requires Plugins: elementor
 *
 * Elementor tested up to: 3.29.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DOPE_CAROUSEL_VERSION', '1.2.2' );
define( 'DOPE_CAROUSEL_FILE', __FILE__ );
define( 'DOPE_CAROUSEL_PATH', __DIR__ );
define( 'DOPE_CAROUSEL_URL', plugin_dir_url( __FILE__ ) );

require_once DOPE_CAROUSEL_PATH . '/includes/class-dope-carousel-plugin.php';

Dope_Carousel_Plugin::instance();
