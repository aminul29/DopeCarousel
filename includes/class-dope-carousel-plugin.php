<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Dope_Carousel_Plugin {
    const MINIMUM_ELEMENTOR_VERSION = '3.20.0';
    const MINIMUM_PHP_VERSION       = '7.4';

    private static $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    public function init(): void {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
            return;
        }

        if ( version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }

        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }

        add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'register_assets' ) );
    }

    public function register_assets(): void {
        wp_register_style(
            'dope-carousel-swiper',
            DOPE_CAROUSEL_URL . 'assets/vendor/swiper/swiper-bundle.min.css',
            array(),
            DOPE_CAROUSEL_VERSION
        );

        wp_register_script(
            'dope-carousel-swiper',
            DOPE_CAROUSEL_URL . 'assets/vendor/swiper/swiper-bundle.min.js',
            array(),
            DOPE_CAROUSEL_VERSION,
            true
        );

        wp_register_style(
            'dope-carousel-widget',
            DOPE_CAROUSEL_URL . 'assets/css/dope-carousel.css',
            array( 'dope-carousel-swiper' ),
            DOPE_CAROUSEL_VERSION
        );

        wp_register_script(
            'dope-carousel-widget',
            DOPE_CAROUSEL_URL . 'assets/js/dope-carousel.js',
            array( 'dope-carousel-swiper' ),
            DOPE_CAROUSEL_VERSION,
            true
        );
    }

    public function register_widgets( $widgets_manager ): void {
        require_once DOPE_CAROUSEL_PATH . '/includes/widgets/class-dope-carousel-widget.php';
        $widgets_manager->register( new Dope_Carousel_Widget() );
    }

    public function admin_notice_missing_elementor(): void {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        echo '<div class="notice notice-warning is-dismissible"><p>';
        echo esc_html__( 'Dope Carousel requires Elementor to be installed and activated.', 'dope-carousel' );
        echo '</p></div>';
    }

    public function admin_notice_minimum_elementor_version(): void {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html(
                sprintf(
                    /* translators: 1: required Elementor version. */
                    __( 'Dope Carousel requires Elementor version %1$s or greater.', 'dope-carousel' ),
                    self::MINIMUM_ELEMENTOR_VERSION
                )
            )
        );
    }

    public function admin_notice_minimum_php_version(): void {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        printf(
            '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
            esc_html(
                sprintf(
                    /* translators: 1: required PHP version. */
                    __( 'Dope Carousel requires PHP version %1$s or greater.', 'dope-carousel' ),
                    self::MINIMUM_PHP_VERSION
                )
            )
        );
    }
}
