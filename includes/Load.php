<?php
/**
 * Loader.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  1.0.3
 */

namespace WebManDesign\CMiNB;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Load {

	/**
	 * Initialization.
	 *
	 * @since    1.0.0
	 * @version  1.0.3
	 *
	 * @return  void
	 */
	public static function init() {

		// Processing

			Block::init();
			Menu::init();
			Options::init();

	} // /init

}
