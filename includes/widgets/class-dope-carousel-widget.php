<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class Dope_Carousel_Widget extends Widget_Base {
    public function get_name(): string {
        return 'dope_carousel';
    }

    public function get_title(): string {
        return esc_html__( 'Dope Carousel', 'dope-carousel' );
    }

    public function get_icon(): string {
        return 'eicon-slider-push';
    }

    public function get_categories(): array {
        return array( 'general' );
    }

    public function get_keywords(): array {
        return array( 'carousel', 'slider', 'swiper', 'ticker', 'gallery' );
    }

    public function get_style_depends(): array {
        return array( 'dope-carousel-swiper', 'dope-carousel-widget' );
    }

    public function get_script_depends(): array {
        return array( 'dope-carousel-widget' );
    }

    protected function register_controls(): void {
        $this->register_slides_controls();
        $this->register_carousel_settings_controls();
        $this->register_style_controls();
    }

    private function register_slides_controls(): void {
        $this->start_controls_section(
            'section_slides',
            array(
                'label' => esc_html__( 'Slides', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'content_source',
            array(
                'label'   => esc_html__( 'Content Source', 'dope-carousel' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => array(
                    'manual'  => esc_html__( 'Manual', 'dope-carousel' ),
                    'gallery' => esc_html__( 'Gallery', 'dope-carousel' ),
                ),
            )
        );

        $this->add_control(
            'gallery_images',
            array(
                'label'       => esc_html__( 'Gallery Images', 'dope-carousel' ),
                'type'        => Controls_Manager::GALLERY,
                'condition'   => array(
                    'content_source' => 'gallery',
                ),
                'description' => esc_html__( 'Bulk select images. Title, caption, description, button text, and button link are read from Media Library metadata.', 'dope-carousel' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_image',
            array(
                'label'   => esc_html__( 'Image', 'dope-carousel' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
            )
        );

        $repeater->add_control(
            'slide_title',
            array(
                'label'       => esc_html__( 'Title', 'dope-carousel' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Carousel Slide', 'dope-carousel' ),
                'label_block' => true,
            )
        );

        $repeater->add_control(
            'slide_description',
            array(
                'label'   => esc_html__( 'Description', 'dope-carousel' ),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Add your slide description here.', 'dope-carousel' ),
            )
        );

        $repeater->add_control(
            'slide_button_text',
            array(
                'label'       => esc_html__( 'Button Text', 'dope-carousel' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Learn More', 'dope-carousel' ),
                'label_block' => true,
            )
        );

        $repeater->add_control(
            'slide_link',
            array(
                'label'       => esc_html__( 'Link', 'dope-carousel' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $this->add_control(
            'slides',
            array(
                'label'       => esc_html__( 'Slides', 'dope-carousel' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'slide_title'       => esc_html__( 'Slide One', 'dope-carousel' ),
                        'slide_description' => esc_html__( 'First sample slide description.', 'dope-carousel' ),
                        'slide_button_text' => esc_html__( 'Learn More', 'dope-carousel' ),
                        'slide_link'        => array(
                            'url' => '#',
                        ),
                    ),
                    array(
                        'slide_title'       => esc_html__( 'Slide Two', 'dope-carousel' ),
                        'slide_description' => esc_html__( 'Second sample slide description.', 'dope-carousel' ),
                        'slide_button_text' => esc_html__( 'Explore', 'dope-carousel' ),
                    ),
                    array(
                        'slide_title'       => esc_html__( 'Slide Three', 'dope-carousel' ),
                        'slide_description' => esc_html__( 'Third sample slide description.', 'dope-carousel' ),
                        'slide_button_text' => esc_html__( 'Get Started', 'dope-carousel' ),
                    ),
                ),
                'title_field' => '{{{ slide_title }}}',
                'condition'   => array(
                    'content_source' => 'manual',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_carousel_settings_controls(): void {
        $this->start_controls_section(
            'section_carousel_settings',
            array(
                'label' => esc_html__( 'Carousel Settings', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'dope-carousel' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slider',
                'options' => array(
                    'slider'     => esc_html__( 'Slider', 'dope-carousel' ),
                    'single_row' => esc_html__( 'Single Row Carousel', 'dope-carousel' ),
                    'double_row' => esc_html__( 'Double Row Carousel', 'dope-carousel' ),
                ),
            )
        );

        $this->add_control(
            'slide_style',
            array(
                'label'   => esc_html__( 'Slide Style', 'dope-carousel' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => array(
                    'slide'  => esc_html__( 'Slide', 'dope-carousel' ),
                    'fade'   => esc_html__( 'Fade', 'dope-carousel' ),
                    'ticker' => esc_html__( 'Ticker', 'dope-carousel' ),
                ),
            )
        );

        $this->add_control(
            'fade_notice',
            array(
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => esc_html__( 'Fade works only with Slider layout. Other layouts automatically fallback to Slide effect.', 'dope-carousel' ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'       => array(
                    'slide_style' => 'fade',
                ),
            )
        );

        $this->add_responsive_control(
            'slides_per_view',
            array(
                'label'              => esc_html__( 'Slides Per View', 'dope-carousel' ),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 3,
                'tablet_default'     => 2,
                'mobile_default'     => 1,
                'min'                => 1,
                'step'               => 1,
                'frontend_available' => true,
            )
        );

        $this->add_responsive_control(
            'space_between',
            array(
                'label'              => esc_html__( 'Space Between Slides', 'dope-carousel' ),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 24,
                'tablet_default'     => 18,
                'mobile_default'     => 12,
                'min'                => 0,
                'step'               => 1,
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'speed',
            array(
                'label'       => esc_html__( 'Transition Speed (ms)', 'dope-carousel' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 700,
                'min'         => 100,
                'step'        => 50,
                'description' => esc_html__( 'Standard slide/fade animation speed.', 'dope-carousel' ),
            )
        );

        $this->add_control(
            'loop',
            array(
                'label'        => esc_html__( 'Loop', 'dope-carousel' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'dope-carousel' ),
                'label_off'    => esc_html__( 'No', 'dope-carousel' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => esc_html__( 'Autoplay', 'dope-carousel' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'dope-carousel' ),
                'label_off'    => esc_html__( 'No', 'dope-carousel' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'autoplay_delay',
            array(
                'label'     => esc_html__( 'Autoplay Delay (ms)', 'dope-carousel' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
                'min'       => 0,
                'step'      => 100,
                'condition' => array(
                    'autoplay'     => 'yes',
                    'slide_style!' => 'ticker',
                ),
            )
        );

        $this->add_control(
            'pause_on_hover',
            array(
                'label'        => esc_html__( 'Pause On Hover', 'dope-carousel' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'dope-carousel' ),
                'label_off'    => esc_html__( 'No', 'dope-carousel' ),
                'return_value' => 'yes',
                'condition'    => array(
                    'autoplay' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_arrows',
            array(
                'label'        => esc_html__( 'Show Arrows', 'dope-carousel' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'dope-carousel' ),
                'label_off'    => esc_html__( 'No', 'dope-carousel' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_dots',
            array(
                'label'        => esc_html__( 'Show Dots', 'dope-carousel' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'dope-carousel' ),
                'label_off'    => esc_html__( 'No', 'dope-carousel' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'allow_drag',
            array(
                'label'        => esc_html__( 'Allow Drag', 'dope-carousel' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'dope-carousel' ),
                'label_off'    => esc_html__( 'No', 'dope-carousel' ),
                'return_value' => 'yes',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ticker_settings',
            array(
                'label'     => esc_html__( 'Ticker Settings', 'dope-carousel' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'slide_style' => 'ticker',
                ),
            )
        );

        $this->add_control(
            'ticker_direction',
            array(
                'label'   => esc_html__( 'Ticker Direction', 'dope-carousel' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'normal',
                'options' => array(
                    'normal' => array(
                        'title' => esc_html__( 'Left to Right', 'dope-carousel' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                    'reverse' => array(
                        'title' => esc_html__( 'Right to Left', 'dope-carousel' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                ),
                'toggle'  => false,
            )
        );

        $this->add_control(
            'ticker_speed',
            array(
                'label'       => esc_html__( 'Ticker Speed (ms)', 'dope-carousel' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 4500,
                'min'         => 1000,
                'step'        => 100,
                'description' => esc_html__( 'Higher values make ticker movement slower.', 'dope-carousel' ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_controls(): void {
        $this->register_style_container_controls();
        $this->register_style_slide_controls();
        $this->register_style_image_controls();
        $this->register_style_title_controls();
        $this->register_style_description_controls();
        $this->register_style_button_controls();
        $this->register_style_navigation_controls();
        $this->register_style_pagination_controls();
    }

    private function register_style_container_controls(): void {
        $this->start_controls_section(
            'section_style_container',
            array(
                'label' => esc_html__( 'Container', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'container_max_width',
            array(
                'label'      => esc_html__( 'Max Width', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%', 'vw' ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1920,
                    ),
                    '%'  => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                    'vw' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel' => 'max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'container_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'dope-carousel' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'dope-carousel' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'dope-carousel' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'dope-carousel' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors_dictionary' => array(
                    'left'   => 'margin-left: 0; margin-right: auto;',
                    'center' => 'margin-left: auto; margin-right: auto;',
                    'right'  => 'margin-left: auto; margin-right: 0;',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel' => '{{VALUE}}',
                ),
                'toggle'    => false,
            )
        );

        $this->add_responsive_control(
            'container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'container_margin',
            array(
                'label'      => esc_html__( 'Margin', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'container_background',
                'selector' => '{{WRAPPER}} .dc-carousel',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'container_border',
                'selector' => '{{WRAPPER}} .dc-carousel',
            )
        );

        $this->add_responsive_control(
            'container_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .dc-carousel',
            )
        );

        $this->end_controls_section();
    }

    private function register_style_slide_controls(): void {
        $this->start_controls_section(
            'section_style_slide_card',
            array(
                'label' => esc_html__( 'Slide Card', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'slide_card_background',
                'selector' => '{{WRAPPER}} .dc-carousel__card',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'slide_card_border',
                'selector' => '{{WRAPPER}} .dc-carousel__card',
            )
        );

        $this->add_responsive_control(
            'slide_card_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'slide_card_shadow',
                'selector' => '{{WRAPPER}} .dc-carousel__card',
            )
        );

        $this->add_responsive_control(
            'slide_card_padding',
            array(
                'label'      => esc_html__( 'Inner Padding', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'slide_content_alignment',
            array(
                'label'   => esc_html__( 'Content Alignment', 'dope-carousel' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'dope-carousel' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'dope-carousel' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'dope-carousel' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__content' => 'text-align: {{VALUE}};',
                ),
                'toggle'    => false,
            )
        );

        $this->add_responsive_control(
            'slide_content_gap',
            array(
                'label'      => esc_html__( 'Content Gap', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 80,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__content' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_image_controls(): void {
        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'image_height',
            array(
                'label'      => esc_html__( 'Height', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'vh', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 900,
                    ),
                    'vh' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__media img' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'image_object_fit',
            array(
                'label'   => esc_html__( 'Object Fit', 'dope-carousel' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => array(
                    'cover'   => esc_html__( 'Cover', 'dope-carousel' ),
                    'contain' => esc_html__( 'Contain', 'dope-carousel' ),
                    'fill'    => esc_html__( 'Fill', 'dope-carousel' ),
                    'none'    => esc_html__( 'None', 'dope-carousel' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__media img' => 'object-fit: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_spacing',
            array(
                'label'      => esc_html__( 'Bottom Spacing', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_title_controls(): void {
        $this->start_controls_section(
            'section_style_title',
            array(
                'label' => esc_html__( 'Title', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .dc-carousel__title',
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label'     => esc_html__( 'Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label'      => esc_html__( 'Margin', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_description_controls(): void {
        $this->start_controls_section(
            'section_style_description',
            array(
                'label' => esc_html__( 'Description', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .dc-carousel__description',
            )
        );

        $this->add_control(
            'description_color',
            array(
                'label'     => esc_html__( 'Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__description' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'description_margin',
            array(
                'label'      => esc_html__( 'Margin', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_button_controls(): void {
        $this->start_controls_section(
            'section_style_button',
            array(
                'label' => esc_html__( 'Button', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .dc-carousel__button',
            )
        );

        $this->start_controls_tabs( 'tabs_button_states' );

        $this->start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'dope-carousel' ),
            )
        );

        $this->add_control(
            'button_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__button' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__button' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__button' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'dope-carousel' ),
            )
        );

        $this->add_control(
            'button_text_color_hover',
            array(
                'label'     => esc_html__( 'Text Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__button:hover, {{WRAPPER}} .dc-carousel__button:focus' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_background_color_hover',
            array(
                'label'     => esc_html__( 'Background Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__button:hover, {{WRAPPER}} .dc-carousel__button:focus' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'button_border_color_hover',
            array(
                'label'     => esc_html__( 'Border Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__button:hover, {{WRAPPER}} .dc-carousel__button:focus' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .dc-carousel__button',
            )
        );

        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_margin',
            array(
                'label'      => esc_html__( 'Margin', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em', 'rem' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_navigation_controls(): void {
        $this->start_controls_section(
            'section_style_navigation',
            array(
                'label' => esc_html__( 'Navigation Arrows', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'arrows_size',
            array(
                'label'      => esc_html__( 'Arrow Size', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 18,
                        'max' => 120,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} * 0.4);',
                ),
            )
        );

        $this->add_control(
            'arrows_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__arrow' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'arrows_background',
            array(
                'label'     => esc_html__( 'Background Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__arrow' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'arrows_border',
                'selector' => '{{WRAPPER}} .dc-carousel__arrow',
            )
        );

        $this->add_responsive_control(
            'arrows_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'dope-carousel' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'arrows_shadow',
                'selector' => '{{WRAPPER}} .dc-carousel__arrow',
            )
        );

        $this->add_responsive_control(
            'arrows_horizontal_offset',
            array(
                'label'      => esc_html__( 'Horizontal Offset', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => -120,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__arrow--prev' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dc-carousel__arrow--next' => 'right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'arrows_vertical_position',
            array(
                'label'      => esc_html__( 'Vertical Position', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                    'px' => array(
                        'min' => -120,
                        'max' => 1200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__arrow' => 'top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    private function register_style_pagination_controls(): void {
        $this->start_controls_section(
            'section_style_pagination',
            array(
                'label' => esc_html__( 'Pagination Bullets', 'dope-carousel' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'pagination_bullet_size',
            array(
                'label'      => esc_html__( 'Bullet Size', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 4,
                        'max' => 32,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'pagination_bullet_spacing',
            array(
                'label'      => esc_html__( 'Bullet Spacing', 'dope-carousel' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 40,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .dc-carousel__pagination' => '--dc-bullet-gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'pagination_bullet_color',
            array(
                'label'     => esc_html__( 'Bullet Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'pagination_bullet_active_color',
            array(
                'label'     => esc_html__( 'Active Bullet Color', 'dope-carousel' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dc-carousel__pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render(): void {
        $settings = $this->get_settings_for_display();
        $slides   = isset( $settings['slides'] ) && is_array( $settings['slides'] ) ? $settings['slides'] : array();
        $source   = isset( $settings['content_source'] ) && 'gallery' === $settings['content_source'] ? 'gallery' : 'manual';

        if ( 'gallery' === $source ) {
            $gallery_items  = isset( $settings['gallery_images'] ) && is_array( $settings['gallery_images'] ) ? $settings['gallery_images'] : array();
            $gallery_slides = $this->build_gallery_slides( $gallery_items );

            if ( ! empty( $gallery_slides ) ) {
                $slides = $gallery_slides;
            }
        }

        if ( empty( $slides ) ) {
            return;
        }

        $layout = isset( $settings['layout'] ) && in_array( $settings['layout'], array( 'slider', 'single_row', 'double_row' ), true )
            ? $settings['layout']
            : 'slider';

        $slide_style = isset( $settings['slide_style'] ) && in_array( $settings['slide_style'], array( 'slide', 'fade', 'ticker' ), true )
            ? $settings['slide_style']
            : 'slide';

        $show_arrows = $this->is_enabled( $settings, 'show_arrows', true );
        $show_dots   = $this->is_enabled( $settings, 'show_dots', true );
        $allow_drag  = $this->is_enabled( $settings, 'allow_drag', true );
        $loop        = $this->is_enabled( $settings, 'loop', true );
        $autoplay    = $this->is_enabled( $settings, 'autoplay', true );

        $slides_per_view_desktop = $this->sanitize_float( $settings['slides_per_view'] ?? 3, 3, 1 );
        $slides_per_view_tablet  = $this->sanitize_float( $settings['slides_per_view_tablet'] ?? $slides_per_view_desktop, $slides_per_view_desktop, 1 );
        $slides_per_view_mobile  = $this->sanitize_float( $settings['slides_per_view_mobile'] ?? 1, 1, 1 );

        $space_between_desktop = $this->sanitize_int( $settings['space_between'] ?? 24, 24, 0 );
        $space_between_tablet  = $this->sanitize_int( $settings['space_between_tablet'] ?? $space_between_desktop, $space_between_desktop, 0 );
        $space_between_mobile  = $this->sanitize_int( $settings['space_between_mobile'] ?? 12, 12, 0 );

        $speed          = $this->sanitize_int( $settings['speed'] ?? 700, 700, 100 );
        $autoplay_delay = $this->sanitize_int( $settings['autoplay_delay'] ?? 3000, 3000, 0 );
        $pause_on_hover = $this->is_enabled( $settings, 'pause_on_hover', true );

        $ticker_direction = isset( $settings['ticker_direction'] ) && in_array( $settings['ticker_direction'], array( 'normal', 'reverse' ), true )
            ? $settings['ticker_direction']
            : 'normal';

        $ticker_speed = $this->sanitize_int( $settings['ticker_speed'] ?? 4500, 4500, 1000 );

        $uid           = wp_unique_id( 'dc-carousel-' );
        $prev_button   = $uid . '-prev';
        $next_button   = $uid . '-next';
        $pagination_id = $uid . '-pagination';

        $config = array(
            'layout'         => $layout,
            'style'          => $slide_style,
            'slidesPerView'  => array(
                'desktop' => $slides_per_view_desktop,
                'tablet'  => $slides_per_view_tablet,
                'mobile'  => $slides_per_view_mobile,
            ),
            'spaceBetween'   => array(
                'desktop' => $space_between_desktop,
                'tablet'  => $space_between_tablet,
                'mobile'  => $space_between_mobile,
            ),
            'speed'          => $speed,
            'loop'           => $loop,
            'autoplay'       => $autoplay,
            'autoplayDelay'  => $autoplay_delay,
            'pauseOnHover'   => $pause_on_hover,
            'arrows'         => $show_arrows,
            'dots'           => $show_dots,
            'drag'           => $allow_drag,
            'tickerDirection'=> $ticker_direction,
            'tickerSpeed'    => $ticker_speed,
            'selectors'      => array(
                'prev'       => $show_arrows ? '#' . $prev_button : '',
                'next'       => $show_arrows ? '#' . $next_button : '',
                'pagination' => $show_dots ? '#' . $pagination_id : '',
            ),
        );

        $wrapper_classes = 'dc-carousel dc-carousel--layout-' . $layout . ' dc-carousel--style-' . $slide_style;

        echo '<div class="' . esc_attr( $wrapper_classes ) . '" id="' . esc_attr( $uid ) . '" data-dc-config="' . esc_attr( wp_json_encode( $config ) ) . '">';
        echo '<div class="dc-carousel__swiper swiper">';
        echo '<div class="swiper-wrapper">';

        foreach ( $slides as $index => $slide ) {
            $image_url = '';

            if ( isset( $slide['slide_image']['url'] ) && '' !== $slide['slide_image']['url'] ) {
                $image_url = $slide['slide_image']['url'];
            }

            if ( '' === $image_url ) {
                $image_url = Utils::get_placeholder_image_src();
            }

            $image_alt = '';
            if ( isset( $slide['slide_image']['id'] ) && absint( $slide['slide_image']['id'] ) > 0 ) {
                $image_alt = get_post_meta( absint( $slide['slide_image']['id'] ), '_wp_attachment_image_alt', true );
                $image_alt = is_string( $image_alt ) ? $image_alt : '';
            }

            $title       = isset( $slide['slide_title'] ) ? $slide['slide_title'] : '';
            $description = isset( $slide['slide_description'] ) ? $slide['slide_description'] : '';
            $button_text = isset( $slide['slide_button_text'] ) ? $slide['slide_button_text'] : '';
            $link        = isset( $slide['slide_link'] ) && is_array( $slide['slide_link'] ) ? $slide['slide_link'] : array();
            $has_link    = isset( $link['url'] ) && '' !== $link['url'];
            $slide_source = isset( $slide['slide_source'] ) ? $slide['slide_source'] : 'manual';

            $button_key = 'slide_button_' . $uid . '_' . $index;

            if ( $has_link ) {
                $this->add_render_attribute( $button_key, 'href', esc_url( $link['url'] ) );
                $this->add_render_attribute( $button_key, 'class', 'dc-carousel__button' );

                if ( ! empty( $link['is_external'] ) ) {
                    $this->add_render_attribute( $button_key, 'target', '_blank' );
                }

                if ( ! empty( $link['nofollow'] ) ) {
                    $this->add_render_attribute( $button_key, 'rel', 'nofollow' );
                }
            }

            echo '<article class="dc-carousel__slide swiper-slide">';
            echo '<div class="dc-carousel__card">';
            echo '<div class="dc-carousel__media">';
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image_alt ) . '" loading="lazy" />';
            echo '</div>';
            echo '<div class="dc-carousel__content">';

            if ( '' !== $title ) {
                echo '<h3 class="dc-carousel__title">' . esc_html( $title ) . '</h3>';
            }

            if ( '' !== $description ) {
                echo '<div class="dc-carousel__description">' . wp_kses_post( $description ) . '</div>';
            }

            $should_render_button = '' !== $button_text && ( $has_link || 'manual' === $slide_source );

            if ( $should_render_button ) {
                if ( $has_link ) {
                    echo '<a ' . $this->get_render_attribute_string( $button_key ) . '>' . esc_html( $button_text ) . '</a>';
                } else {
                    echo '<span class="dc-carousel__button dc-carousel__button--static">' . esc_html( $button_text ) . '</span>';
                }
            }

            echo '</div>';
            echo '</div>';
            echo '</article>';
        }

        echo '</div>';
        echo '</div>';

        if ( $show_arrows ) {
            echo '<button type="button" class="dc-carousel__arrow dc-carousel__arrow--prev" id="' . esc_attr( $prev_button ) . '" aria-label="' . esc_attr__( 'Previous slide', 'dope-carousel' ) . '">';
            echo '<span aria-hidden="true">&#10094;</span>';
            echo '</button>';
            echo '<button type="button" class="dc-carousel__arrow dc-carousel__arrow--next" id="' . esc_attr( $next_button ) . '" aria-label="' . esc_attr__( 'Next slide', 'dope-carousel' ) . '">';
            echo '<span aria-hidden="true">&#10095;</span>';
            echo '</button>';
        }

        if ( $show_dots ) {
            echo '<div class="dc-carousel__pagination swiper-pagination" id="' . esc_attr( $pagination_id ) . '" aria-label="' . esc_attr__( 'Carousel pagination', 'dope-carousel' ) . '"></div>';
        }

        echo '</div>';
    }

    private function build_gallery_slides( array $gallery_items ): array {
        $slides = array();

        foreach ( $gallery_items as $item ) {
            $attachment_id = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
            if ( $attachment_id <= 0 ) {
                continue;
            }

            $attachment = get_post( $attachment_id );
            if ( ! $attachment || 'attachment' !== $attachment->post_type || ! wp_attachment_is_image( $attachment_id ) ) {
                continue;
            }

            $meta_payload = $this->get_attachment_meta_payload( $attachment_id );
            $image_url    = wp_get_attachment_image_url( $attachment_id, 'full' );

            if ( ! is_string( $image_url ) || '' === $image_url ) {
                continue;
            }

            $description_html = '';

            if ( '' !== $meta_payload['caption'] ) {
                $description_html .= '<p class="dc-carousel__caption">' . esc_html( $meta_payload['caption'] ) . '</p>';
            }

            if ( '' !== $meta_payload['description'] ) {
                $description_html .= wpautop( wp_kses_post( $meta_payload['description'] ) );
            }

            $slides[] = array(
                'slide_image'       => array(
                    'id'  => $attachment_id,
                    'url' => $image_url,
                ),
                'slide_title'       => $meta_payload['title'],
                'slide_description' => $description_html,
                'slide_button_text' => $meta_payload['button_text'],
                'slide_link'        => array(
                    'url' => $meta_payload['button_link'],
                ),
                'slide_source'      => 'gallery',
            );
        }

        return $slides;
    }

    private function get_attachment_meta_payload( int $attachment_id ): array {
        $title       = get_the_title( $attachment_id );
        $caption     = wp_get_attachment_caption( $attachment_id );
        $attachment  = get_post( $attachment_id );
        $description = $attachment instanceof WP_Post ? (string) $attachment->post_content : '';
        $button_text = get_post_meta( $attachment_id, '_dc_carousel_button_text', true );
        $button_link = get_post_meta( $attachment_id, '_dc_carousel_button_link', true );

        return array(
            'title'       => is_string( $title ) ? $title : '',
            'caption'     => is_string( $caption ) ? $caption : '',
            'description' => is_string( $description ) ? $description : '',
            'button_text' => is_string( $button_text ) ? sanitize_text_field( $button_text ) : '',
            'button_link' => is_string( $button_link ) ? esc_url_raw( $button_link ) : '',
        );
    }

    private function is_enabled( array $settings, string $key, bool $default = false ): bool {
        if ( ! array_key_exists( $key, $settings ) ) {
            return $default;
        }

        return 'yes' === $settings[ $key ];
    }

    private function sanitize_int( $value, int $default, int $min = 0 ): int {
        $int = absint( $value );

        if ( $int < $min ) {
            return $default;
        }

        return $int;
    }

    private function sanitize_float( $value, float $default, float $min = 0 ): float {
        if ( is_numeric( $value ) ) {
            $float = (float) $value;
            if ( $float >= $min ) {
                return $float;
            }
        }

        return $default;
    }
}
