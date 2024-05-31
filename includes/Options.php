<?php
/**
 * Customization options.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.3
 */

namespace WebManDesign\CMiNB;

use WP_Customize_Manager;
use WP_Customize_Control;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Options {

	/**
	 * Plugin option IDs.
	 *
	 * @since  1.0.3
	 *
	 * @var array
	 */
	public static $id = array(
		'section'              => 'cminb',
		'cache'                => 'cminb_cache',
		'menu_locations_count' => 'cminb_menu_locations_count',
	);

	/**
	 * Initialization.
	 *
	 * @since  1.0.3
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'customize_register', __CLASS__ . '::options', 20 );
				add_action( 'customize_register', __CLASS__ . '::pointers', 20 );

	} // /init

	/**
	 * Customizer options.
	 *
	 * @since  1.0.3
	 *
	 * @param  WP_Customize_Manager $wp_customize
	 *
	 * @return  void
	 */
	public static function options( WP_Customize_Manager $wp_customize ) {

		// Processing

			$wp_customize->add_section(
				self::$id['section'],
				array(
					'title'      => esc_html__( 'Classic Menu in Navigation Block', 'classic-menu-in-navigation-block' ),
					'panel'      => 'nav_menus',
					'priority'   => 900,
					'capability' => 'edit_theme_options',
				)
			);

			$wp_customize->add_setting(
				self::$id['cache'],
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => function( $value ): bool {
						return (bool) $value;
					},
				)
			);

				$wp_customize->add_control(
					self::$id['cache'],
					array(
						'type'        => 'checkbox',
						'section'     => self::$id['section'],
						'priority'    => 0,
						'label'       => esc_html__( 'Block menu cache', 'classic-menu-in-navigation-block' ),
						'description' => esc_html__( 'With some multilingual plugins (such as WPML) it may help to disable this cache.', 'classic-menu-in-navigation-block' ),
					)
				);

			$wp_customize->add_setting(
				self::$id['menu_locations_count'],
				array(
					'capability'        => 'edit_theme_options',
					'default'           => 3,
					'sanitize_callback' => 'absint',
				)
			);

				$wp_customize->add_control(
					self::$id['menu_locations_count'],
					array(
						'type'        => 'number',
						'section'     => self::$id['section'],
						'label'       => esc_html__( 'Number of menu locations', 'classic-menu-in-navigation-block' ),
						'input_attrs' => array(
							'min'  => 1,
							'max'  => 5,
							'step' => 1,
						),
					)
				);

	} // /options

	/**
	 * Setup partial refresh pointers.
	 *
	 * @since  1.0.3
	 *
	 * @param  WP_Customize_Manager $wp_customize
	 *
	 * @return  void
	 */
	public static function pointers( WP_Customize_Manager $wp_customize ) {

		// Processing

			$wp_customize->selective_refresh->add_partial( self::$id['cache'], array(
				'selector' => '.has-classic-menu',
			) );

	} // /pointers

}
