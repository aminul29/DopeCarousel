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
        add_filter( 'attachment_fields_to_edit', array( $this, 'add_media_carousel_fields' ), 10, 2 );
        add_filter( 'attachment_fields_to_save', array( $this, 'save_media_carousel_fields' ), 10, 2 );
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

    public function add_media_carousel_fields( array $form_fields, WP_Post $post ): array {
        $button_text = get_post_meta( $post->ID, '_dc_carousel_button_text', true );
        $button_link = get_post_meta( $post->ID, '_dc_carousel_button_link', true );

        $form_fields['dc_carousel_button_text'] = array(
            'label' => esc_html__( 'Carousel Button Text', 'dope-carousel' ),
            'input' => 'text',
            'value' => is_string( $button_text ) ? $button_text : '',
            'helps' => esc_html__( 'Used by Dope Carousel when Content Source is Gallery.', 'dope-carousel' ),
        );

        $form_fields['dc_carousel_button_link'] = array(
            'label' => esc_html__( 'Carousel Button Link', 'dope-carousel' ),
            'input' => 'text',
            'value' => is_string( $button_link ) ? $button_link : '',
            'helps' => esc_html__( 'Add a full URL (e.g. https://example.com). Used in Gallery mode.', 'dope-carousel' ),
        );

        return $form_fields;
    }

    public function save_media_carousel_fields( array $post, array $attachment ): array {
        if ( isset( $attachment['dc_carousel_button_text'] ) ) {
            $button_text = sanitize_text_field( wp_unslash( (string) $attachment['dc_carousel_button_text'] ) );

            if ( '' !== $button_text ) {
                update_post_meta( $post['ID'], '_dc_carousel_button_text', $button_text );
            } else {
                delete_post_meta( $post['ID'], '_dc_carousel_button_text' );
            }
        }

        if ( isset( $attachment['dc_carousel_button_link'] ) ) {
            $button_link = esc_url_raw( trim( wp_unslash( (string) $attachment['dc_carousel_button_link'] ) ) );

            if ( '' !== $button_link ) {
                update_post_meta( $post['ID'], '_dc_carousel_button_link', $button_link );
            } else {
                delete_post_meta( $post['ID'], '_dc_carousel_button_link' );
            }
        }

        return $post;
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
