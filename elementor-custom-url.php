<?php
/**
 * Plugin Name: Custom URL for Elementor
 * Description: Adds custom URL functionality to Elementor's "Container", "Section", "Column", and "Inner Section" elements.
 * Version: 2.0.0
 * Author: Woologger
 * Author URI: https://woologger.com
 * Text Domain: custom-url-for-elementor
 * Requires at least: 5.6
 * Requires PHP: 7.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Elementor tested up to: 3.24.0
 * Elementor Pro tested up to: 3.24.0
 *
 * @package Custom_URL_For_Elementor
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_URL_For_Elementor {
    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    public function init() {
        load_plugin_textdomain( 'custom-url-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'elementor_missing_notice' ) );
            return;
        }

        $this->add_elementor_hooks();
    }

    private function add_elementor_hooks() {
        $elements = ['container', 'section', 'column', 'inner-section'];
        foreach ( $elements as $element ) {
            add_action( "elementor/element/{$element}/section_layout/after_section_end", array( $this, 'add_custom_url_control' ) );
            add_action( "elementor/frontend/{$element}/before_render", array( $this, 'add_url_attribute' ) );
        }
        add_action( 'elementor/element/parse_css', array( $this, 'add_custom_css' ), 10, 2 );
    }

    public function elementor_missing_notice() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '%1$s requires %2$s to be installed and activated.', 'custom-url-for-elementor' ),
            '<strong>' . esc_html__( 'Custom URL for Elementor', 'custom-url-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'custom-url-for-elementor' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
    }

    public function add_custom_url_control( $element ) {
        $element->start_controls_section(
            'section_custom_url',
            [
                'label' => '<i class="eicon-link"></i> ' . esc_html__( 'Custom URL', 'custom-url-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_LAYOUT,
            ]
        );

        $element->add_control(
            'container_url',
            [
                'label'       => esc_html__( 'Element URL', 'custom-url-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com', 'custom-url-for-elementor' ),
                'dynamic'     => [
                    'active' => true,
                ],
            ]
        );

        $element->add_control(
            'open_in_new_tab',
            [
                'label'        => esc_html__( 'Open in New Tab', 'custom-url-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'custom-url-for-elementor' ),
                'label_off'    => esc_html__( 'No', 'custom-url-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $element->add_control(
            'custom_css',
            [
                'label' => esc_html__( 'Custom CSS', 'custom-url-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'rows' => 10,
                'default' => '',
                'description' => esc_html__( 'Add custom CSS styles for this element.', 'custom-url-for-elementor' ),
            ]
        );

        $element->add_control(
            'woologger_credit',
            [
                'type'      => \Elementor\Controls_Manager::RAW_HTML,
                'raw'       => '<p style="text-align: left; font-size: 12px;">' . wp_kses_post( __( 'Designed for free by <a href="https://woologger.com" target="_blank">woologger.com</a> ❤️', 'custom-url-for-elementor' ) ) . '</p>',
                'separator' => 'before',
            ]
        );

        $element->end_controls_section();
    }

    public function add_url_attribute( $element ) {
        $settings = $element->get_settings_for_display();
        $url = isset( $settings['container_url'] ) ? $settings['container_url'] : '';
        if ( $url && ! empty( $url['url'] ) ) {
            $target = $settings['open_in_new_tab'] === 'yes' ? '_blank' : '_self';
            $element->add_render_attribute(
                '_wrapper',
                [
                    'data-url' => esc_url( $url['url'] ),
                    'onclick'  => sprintf( "window.open('%s', '%s');", esc_js( $url['url'] ), esc_js( $target ) ),
                    'style'    => 'cursor: pointer;',
                ]
            );
        }
    }

    public function add_custom_css( $post_css_file, $element ) {
        $settings = $element->get_settings_for_display();

        if ( empty( $settings['custom_css'] ) ) {
            return;
        }

        $custom_css = trim( $settings['custom_css'] );
        
        if ( empty( $custom_css ) ) {
            return;
        }

        $custom_css = str_replace( 'selector', $post_css_file->get_element_unique_selector( $element ), $custom_css );

        $post_css_file->get_stylesheet()->add_raw_css( $custom_css );
    }
}

function custom_url_for_elementor() {
    return Custom_URL_For_Elementor::get_instance();
}

custom_url_for_elementor();