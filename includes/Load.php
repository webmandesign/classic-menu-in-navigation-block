<?php
/**
 * Loader.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

namespace WebManDesign\CMiNB;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Load {

	/**
	 * Initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			Block::init();
			Menu::init();

			// Actions

				add_action( 'init', __CLASS__ . '::load_plugin_textdomain' );

	} // /init

	/**
	 * Localization initialization.
	 *
	 * @since  1.0.0
	 *
	 * @return  void
	 */
	public static function load_plugin_textdomain() {

		// Processing

			load_plugin_textdomain( 'classic-menu-in-navigation-block', false, CMINB_PATH . '/languages' );

	} // /load_plugin_textdomain

}
