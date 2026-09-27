<?php
/**
 * Plugin Name:       Custom URL for Elementor
 * Plugin URI:        https://github.com/woologger/custom-url-for-elementor
 * Description:       Makes Elementor Container, Section, Inner Section and Column elements clickable with a custom URL, and adds per-element custom CSS.
 * Version:           2.1.0
 * Author:            Woologger
 * Author URI:        https://woologger.com
 * Text Domain:       custom-url-for-elementor
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Elementor tested up to:     4.0.8
 * Elementor Pro tested up to: 4.0.8
 *
 * @package Custom_URL_For_Elementor
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CUFE_VERSION', '2.1.0' );
define( 'CUFE_FILE', __FILE__ );
define( 'CUFE_URL', plugin_dir_url( __FILE__ ) );

final class Custom_URL_For_Elementor {

	/**
	 * Controls section after which the "Custom URL" section is injected, per element type.
	 * Inner sections are `section` elements, so they are covered by the `section` entry.
	 */
	const ELEMENT_SECTIONS = array(
		'container' => 'section_layout_container',
		'section'   => 'section_layout',
		'column'    => 'layout',
	);

	/**
	 * URL protocols allowed for element links. `javascript:` and `data:` are never allowed.
	 */
	const ALLOWED_PROTOCOLS = array( 'http', 'https', 'mailto', 'tel', 'sms' );

	/** @var self|null */
	private static $instance = null;

	public static function get_instance(): self {
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
			add_action( 'admin_notices', array( $this, 'elementor_missing_notice' ) );
			return;
		}

		foreach ( self::ELEMENT_SECTIONS as $element => $section_id ) {
			add_action( "elementor/element/{$element}/{$section_id}/after_section_end", array( $this, 'register_controls' ) );
			add_action( "elementor/frontend/{$element}/before_render", array( $this, 'add_link_attributes' ) );
		}

		if ( $this->has_own_custom_css() ) {
			add_action( 'elementor/element/parse_css', array( $this, 'add_custom_css' ), 10, 2 );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
	}

	/**
	 * Elementor Pro ships its own "Custom CSS" control stored under the same `custom_css`
	 * setting key. When Pro is active we defer to it, so existing CSS keeps working and
	 * the controls do not collide.
	 */
	private function has_own_custom_css(): bool {
		return (bool) apply_filters( 'cufe_enable_custom_css', ! defined( 'ELEMENTOR_PRO_VERSION' ) );
	}

	public function elementor_missing_notice(): void {
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

	public function register_assets(): void {
		wp_register_script(
			'custom-url-for-elementor',
			CUFE_URL . 'assets/js/frontend.js',
			array(),
			CUFE_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_register_style( 'custom-url-for-elementor', CUFE_URL . 'assets/css/frontend.css', array(), CUFE_VERSION );
	}

	/**
	 * @param \Elementor\Element_Base $element
	 */
	public function register_controls( $element ): void {
		$element->start_controls_section(
			'section_custom_url',
			array(
				'label' => esc_html__( 'Custom URL', 'custom-url-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_LAYOUT,
			)
		);

		$element->add_control(
			'container_url',
			array(
				'label'       => esc_html__( 'Element URL', 'custom-url-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://example.com', 'custom-url-for-elementor' ),
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		// Kept for backward compatibility with 1.x/2.0 content; the URL control's own
		// "Open in new window" option works as well.
		$element->add_control(
			'open_in_new_tab',
			array(
				'label'        => esc_html__( 'Open in New Tab', 'custom-url-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-url-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'custom-url-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$element->add_control(
			'cufe_aria_label',
			array(
				'label'       => esc_html__( 'Accessible Label', 'custom-url-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Optional. Read by screen readers instead of the element content, e.g. "Read the full article".', 'custom-url-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'ai'          => array(
					'active' => false,
				),
			)
		);

		if ( $this->has_own_custom_css() ) {
			$element->add_control(
				'custom_css',
				array(
					'label'       => esc_html__( 'Custom CSS', 'custom-url-for-elementor' ),
					'type'        => \Elementor\Controls_Manager::CODE,
					'language'    => 'css',
					'rows'        => 10,
					'default'     => '',
					'separator'   => 'before',
					'description' => esc_html__( 'Use "selector" to target this element, e.g. selector:hover { opacity: .8; }', 'custom-url-for-elementor' ),
				)
			);
		}

		$element->add_control(
			'woologger_credit',
			array(
				'type'      => \Elementor\Controls_Manager::RAW_HTML,
				'raw'       => '<p style="text-align: left; font-size: 12px;">' . wp_kses_post( __( 'Designed for free by <a href="https://woologger.com" target="_blank" rel="noopener">woologger.com</a> ❤️', 'custom-url-for-elementor' ) ) . '</p>',
				'separator' => 'before',
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Adds the data attributes the frontend script uses to make the element clickable.
	 *
	 * @param \Elementor\Element_Base $element
	 */
	public function add_link_attributes( $element ): void {
		$settings = $element->get_settings_for_display();

		// A container rendered as <a> already has its own native link.
		if ( isset( $settings['html_tag'] ) && 'a' === $settings['html_tag'] ) {
			return;
		}

		$link     = isset( $settings['container_url'] ) && is_array( $settings['container_url'] ) ? $settings['container_url'] : array();
		$raw_url  = isset( $link['url'] ) ? trim( (string) $link['url'] ) : '';

		if ( '' === $raw_url ) {
			return;
		}

		$url = esc_url( $raw_url, self::ALLOWED_PROTOCOLS );
		if ( '' === $url ) {
			return;
		}

		$new_tab = ! empty( $link['is_external'] ) || ( isset( $settings['open_in_new_tab'] ) && 'yes' === $settings['open_in_new_tab'] );

		$attributes = array(
			'class'         => 'cufe-link',
			'data-cufe-url' => $url,
			'role'          => 'link',
			'tabindex'      => '0',
		);

		if ( $new_tab ) {
			$attributes['data-cufe-target'] = '_blank';
		}

		$aria_label = isset( $settings['cufe_aria_label'] ) ? trim( wp_strip_all_tags( (string) $settings['cufe_aria_label'] ) ) : '';
		if ( '' !== $aria_label ) {
			$attributes['aria-label'] = $aria_label;
		}

		$element->add_render_attribute( '_wrapper', $attributes );

		wp_enqueue_script( 'custom-url-for-elementor' );
		wp_enqueue_style( 'custom-url-for-elementor' );
	}

	/**
	 * @param \Elementor\Core\Files\CSS\Post $post_css_file
	 * @param \Elementor\Element_Base        $element
	 */
	public function add_custom_css( $post_css_file, $element ): void {
		if ( ! isset( self::ELEMENT_SECTIONS[ $element->get_name() ] ) ) {
			return;
		}

		$custom_css = $element->get_settings( 'custom_css' );
		if ( ! is_string( $custom_css ) ) {
			return;
		}

		$custom_css = $this->sanitize_css( $custom_css );
		if ( '' === $custom_css ) {
			return;
		}

		$custom_css = preg_replace( '/\bselector\b/', $post_css_file->get_element_unique_selector( $element ), $custom_css );

		$post_css_file->get_stylesheet()->add_raw_css( $custom_css );
	}

	/**
	 * Removes anything that could break out of the generated stylesheet or inline <style> tag.
	 */
	private function sanitize_css( string $css ): string {
		$css = wp_strip_all_tags( $css );
		$css = str_replace( '<', '', $css );
		// `expression()` and `behavior:` only ever worked in legacy IE, but have no legitimate use.
		$css = preg_replace( '/expression\s*\(|behavior\s*:|-moz-binding\s*:/i', '', $css );
		return trim( (string) $css );
	}
}

function custom_url_for_elementor(): Custom_URL_For_Elementor {
	return Custom_URL_For_Elementor::get_instance();
}

custom_url_for_elementor();
