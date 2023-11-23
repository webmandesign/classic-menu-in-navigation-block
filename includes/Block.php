<?php
/**
 * Block.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.0.1
 */

namespace WebManDesign\CMiNB;

use WP_Classic_To_Block_Menu_Converter;
use WP_Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Block {

	/**
	 * Soft cache for currently set menu location in Navigation block.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string
	 */
	private static $current_menu_location = '';

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

				add_action( 'enqueue_block_editor_assets', __CLASS__ . '::enqueue_scripts' );

			// Filters

				add_filter( 'render_block_data', __CLASS__ . '::render_block_data', 5 );

	} // /init

	/**
	 * Enqueue block scripts.
	 *
	 * @since    1.0.0
	 * @version  1.0.1
	 *
	 * @return  void
	 */
	public static function enqueue_scripts() {

		// Variables

			$handle         = 'classic-menu-in-navigation-block';
			$menu_locations = array( '{label:"' . esc_attr_x( '– Do not display classic menu', 'No menu location selector option', 'classic-menu-in-navigation-block' ) . '",value:""}' );


		// Processing

			// Get registered menu locations.

				foreach ( get_registered_nav_menus() as $id => $label ) {
					$menu_locations[] = '{label:"' . esc_js( $label ) . '",value:"' . esc_js( $id ) . '"}';
				}

			// Registering and enqueuing scripts.

				wp_register_script(
					$handle,
					CMINB_URL . 'blocks/navigation/mods.js',
					array(
						'wp-hooks',
						'wp-element',
						'wp-compose',
						'wp-components',
						'wp-block-editor',
						'wp-polyfill',
					),
					'v' . CMINB_VERSION
				);

				wp_add_inline_script(
					$handle,
					'var ClassicMenuInNavigationBlockData={'
					. 'menuLocations:[' . implode( ',', $menu_locations ) . '],'
					. 'texts:' . wp_json_encode( array(
						'panel' => array(
							'title'       => esc_html__( 'Classic menu', 'classic-menu-in-navigation-block' ),
							'description' => esc_html__( 'Use these options if you want to display a classic menu instead of Navigation block menu items.', 'classic-menu-in-navigation-block' ),
						),
						'link' => array(
							'label' => esc_html__( 'Edit classic menus →', 'classic-menu-in-navigation-block' ),
							'url'   => esc_url( admin_url( 'nav-menus.php' ) ),
						),
						'control' => array(
							'location' => array(
								'label'       => esc_html__( 'Menu location', 'classic-menu-in-navigation-block' ),
								'description' => esc_html__( 'Which classic menu location should be displayed here?', 'classic-menu-in-navigation-block' ),
							),
						),
						'notice' => array(
							'content'  => esc_html__( 'Please note these options can not be previewed in editor. They apply on front-end of your website.', 'classic-menu-in-navigation-block' ),
							'dashicon' => 'hidden',
						),
					) )
					.'};',
					'before'
				);

				wp_enqueue_script( $handle );

	} // /enqueue_scripts

	/**
	 * Prepare Navigation block output modifications.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $block
	 *
	 * @return  array
	 */
	public static function render_block_data( array $block ): array {

		// Processing

			if ( 'core/navigation' === $block['blockName'] ) {
				if ( ! empty( $block['attrs']['menuLocation'] ) ) {

					// Prevent rendering Navigation block menu.
					unset( $block['attrs']['ref'] );

					// Set classic menu as inner blocks.
					$block['innerBlocks'] = self::get_classic_menu_blocks( $block['attrs']['menuLocation'] );

					/**
					 * Unfortunately, we also need to set fallback inner blocks.
					 *
					 * These will get processed only in certain cases, mostly when Navigation
					 * block is displayed in template (part).
					 *
					 * Basically, if the above code works, this filter will not be executed.
					 * And vice versa.
					 *
					 * @see  WP_Block->render()->call_user_func(...)
					 */
					add_filter( 'block_core_navigation_render_fallback', __CLASS__ . '::render_fallback', 5 );

					// Cache the block menu location attribute as we need it in `self::render_fallback()`.
					self::$current_menu_location = $block['attrs']['menuLocation'];

				} else {

					/**
					 * Make sure the classic menu fallback is not applied
					 * when no menu location is set for Navigation block.
					 */
					remove_filter( 'block_core_navigation_render_fallback', __CLASS__ . '::render_fallback', 5 );
				}
			}


		// Output

			return $block;

	} // /render_block_data

	/**
	 * Renders classic menu as blocks.
	 *
	 * @since    1.0.0
	 * @version  1.0.1
	 *
	 * @param  string $menu_location
	 *
	 * @return  array
	 */
	public static function get_classic_menu_blocks( string $menu_location ): array {

		// Requirements check

			if ( ! is_callable( 'WP_Classic_To_Block_Menu_Converter::convert' ) ) {
				return array();
			}


		// Variables

			$locations = get_nav_menu_locations(); // Do not soft cache this!

			foreach ( $locations as $location => $assigned_menu ) {
				if ( 0 === strpos( $menu_location, $location ) ) {
					$menu_location = $location;
					break;
				}
			}

			$menu_args = array(
				'theme_location' => $menu_location,
			);

			// Make sure to filter the classic menu arguments with WP native filters.
			$menu_args = (array) apply_filters( 'wp_nav_menu_args', wp_parse_args( $menu_args, Menu::$args ) );

			// First, get the classic menu based on the requested menu.
			$menu = wp_get_nav_menu_object( $menu_args['menu'] );

			// Afterwards, get the classic menu based on menu location.
			if (
				empty( $menu )
				&& ! empty( $menu_args['theme_location'] )
				&& ! empty( $locations[ $menu_args['theme_location'] ] )
			) {
				$menu = wp_get_nav_menu_object( $locations[ $menu_args['theme_location'] ] );
			}


		// Processing

			if ( $menu ) {

				// Return cached data first.
				$cached = Cache::get( $menu->term_id );
				if (
					true === CMINB_USE_CACHE
					&& ! empty( $cached )
				) {

					return (array) $cached;
				}

				// We have no cached data, so we need to get classic menu as blocks.
				$menu_blocks = WP_Classic_To_Block_Menu_Converter::convert( $menu );

				if (
					! is_wp_error( $menu_blocks )
					&& ! empty( $menu_blocks )
				) {

					$menu_blocks = parse_blocks( $menu_blocks );

					// Cache the data first.
					Cache::set( $menu->term_id, (array) $menu_blocks );

					return $menu_blocks;
				}
			} else {

				if ( current_user_can( 'edit_theme_options' ) ) {

					$menu_locations = get_registered_nav_menus();
					$menu_location  = ( ! empty( $menu_locations[ $menu_location ] ) ) ? ( $menu_locations[ $menu_location ] ) : ( esc_html_x( '(non-existing)', 'Non-existing navigational menu location placeholder', 'classic-menu-in-navigation-block' ) );

					return array(
						array(
							'blockName' => 'core/navigation-link',
							'attrs' => array(
								'className' => 'has-vivid-red-background-color has-white-color',
								'url'       => esc_url( admin_url( 'nav-menus.php?action=locations' ) ),
								'label'     => '&ensp;' . sprintf(
									/* translators: %s: menu location label. */
									esc_attr__( 'Error: No menu at "%s" location.', 'classic-menu-in-navigation-block' ),
									'<strong>' . $menu_location . '</strong>'
								) . '&ensp;',
							),
						),
					);
				} else {

					return array();
				}
			}

	} // /get_classic_menu_blocks

	/**
	 * Rendering classic menu as Navigation block fallback inner blocks.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $fallback_blocks
	 *
	 * @return  array
	 */
	public static function render_fallback( array $fallback_blocks ): array {

		// Variables

			$classic_menu_blocks = self::get_classic_menu_blocks( self::$current_menu_location );


		// Output

			if ( ! empty( $classic_menu_blocks ) ) {
				return $classic_menu_blocks;
			} else {
				return $fallback_blocks;
			}

	} // /render_fallback

}
