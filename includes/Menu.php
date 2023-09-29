<?php
/**
 * Menu.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\CMiNB;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Menu {

	/**
	 * Default `wp_nav_menu()` arguments.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/wp_nav_menu/
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     array
	 */
	public static $args = array(
		'menu'                 => '',
		'container'            => 'div',
		'container_class'      => '',
		'container_id'         => '',
		'container_aria_label' => '',
		'menu_class'           => 'menu',
		'menu_id'              => '',
		'echo'                 => true,
		'fallback_cb'          => 'wp_page_menu',
		'before'               => '',
		'after'                => '',
		'link_before'          => '',
		'link_after'           => '',
		'items_wrap'           => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'item_spacing'         => 'preserve',
		'depth'                => 0,
		'walker'               => '',
		'theme_location'       => '',
	);

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			// Actions

				add_action( 'after_setup_theme', __CLASS__ . '::register', 5 );

				add_action( 'wp_update_nav_menu', __NAMESPACE__ . '\Cache::delete' );
				add_action( 'wp_delete_nav_menu', __NAMESPACE__ . '\Cache::delete' );

	} // /init

	/**
	 * Register custom menu locations.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function register() {

		// Processing

			register_nav_menus(
				array_slice(
					array(
						'primary'    => esc_html_x( 'Primary', 'Navigational menu location label', 'classic-menu-in-navigation-block' ),
						'secondary'  => esc_html_x( 'Secondary', 'Navigational menu location label', 'classic-menu-in-navigation-block' ),
						'tertiary'   => esc_html_x( 'Tertiary', 'Navigational menu location label', 'classic-menu-in-navigation-block' ),
						'quaternary' => esc_html_x( 'Quaternary', 'Navigational menu location label', 'classic-menu-in-navigation-block' ),
						'quinary'    => esc_html_x( 'Quinary', 'Navigational menu location label', 'classic-menu-in-navigation-block' ),
					),
					0,
					max( 1, absint( get_option( 'cminb_menu_locations_count', 3 ) ) )
				)
			);

	} // /register

}
