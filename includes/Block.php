<?php
/**
 * Block.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\CMiNB;

use WP_Classic_To_Block_Menu_Converter;
use WP_Error;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Block {

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

				add_filter( 'render_block_data', __CLASS__ . '::render_block_data', 5, 2 );

	} // /init

	/**
	 * Enqueue block scripts.
	 *
	 * @since  1.0.0
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
					. 'texts:' . json_encode( array(
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

			if (
				'core/navigation' === $block['blockName']
				&& ! empty( $block['attrs']['menuLocation'] )
			) {

				// Prevent rendering Navigation block menu.
				unset( $block['attrs']['ref'] );

				// Set classic menu as inner blocks.
				$block['innerBlocks'] = self::get_classic_menu_blocks( $block['attrs']['menuLocation'] );
			}


		// Output

			return $block;

	} // /render_block_data

	/**
	 * Renders classic menu as blocks.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $menu_location
	 *
	 * @return  array
	 */
	public static function get_classic_menu_blocks( string $menu_location ): array {
/////////// @TODO INTRODUCE TRANSIENT CACHE!

		// Requirements check

			if ( ! is_callable( 'WP_Classic_To_Block_Menu_Converter::convert' ) ) {
				return array();
			}


		// Variables

			$menu_args = array(
				'theme_location' => $menu_location,
			);

			// Make sure to filter the classic menu arguments with WP native filters.
			$menu_args = (array) apply_filters( 'wp_nav_menu_args', wp_parse_args( $menu_args, Menu::$args ) );

			// First, get the classic menu based on the requested menu.
			$menu = wp_get_nav_menu_object( $menu_args['menu'] );

			// Afterwards, get the classic menu based on menu location.
			$locations = get_nav_menu_locations(); // Do not soft cache this!
			if (
				empty( $menu )
				&& ! empty( $menu_args['theme_location'] )
				&& ! empty( $locations[ $menu_args['theme_location'] ] )
			) {
				$menu = wp_get_nav_menu_object( $locations[ $menu_args['theme_location'] ] );
			}


		// Processing

			if ( $menu ) {

				$menu_blocks = WP_Classic_To_Block_Menu_Converter::convert( $menu );

				if (
					! is_wp_error( $menu_blocks )
					&& ! empty( $menu_blocks )
				) {

					return parse_blocks( $menu_blocks );
				}
			} else {

				if ( current_user_can( 'edit_theme_options' ) ) {

					$menu_locations = get_registered_nav_menus();
					$menu_location  = ( ! empty( $menu_locations[ $menu_location ] ) ) ? ( $menu_locations[ $menu_location ] ) : ( esc_html_x( '{non-existing}', 'Non-existing navigational menu location placeholder', 'classic-menu-in-navigation-block' ) );

					return array(
						array(
							'blockName' => 'core/navigation-link',
							'attrs' => array(
								'label' => sprintf(
									/* translators: %s: menu location label. */
									esc_attr__( 'Error: No menu at "%s" location.', 'classic-menu-in-navigation-block' ),
									$menu_location
								),
								'url' => esc_url( admin_url( 'nav-menus.php?action=locations' ) ),
							),
						),
					);
				} else {

					return array();
				}
			}

	} // /get_classic_menu_blocks

}
