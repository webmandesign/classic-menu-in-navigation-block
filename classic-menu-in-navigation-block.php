<?php
/**
 * @todo
 * Plugin Name:  Classic Menu in Navigation Block
 * Plugin URI:   https://www.webmandesign.eu/portfolio/classic-menu-in-navigation-block-wordpress-plugin/
 * Description:  Extending WordPress Navigation block with functionality to display classic menus.
 * Version:      1.0.0
 * Author:       WebMan Design, Oliver Juhas
 * Author URI:   https://www.webmandesign.eu/
 * License:      GNU General Public License v3
 * License URI:  http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:  classic-menu-in-navigation-block
 * Domain Path:  /languages
 *
 * Requires PHP:       7.0
 * Requires at least:  6.2
 *
 * GitHub Plugin URI:  https://github.com/webmandesign/classic-menu-in-navigation-block
 *
 * @copyright  WebMan Design, Oliver Juhas
 * @license    GPL-3.0, https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @link  https://github.com/webmandesign/classic-menu-in-navigation-block
 * @link  https://www.webmandesign.eu
 *
 * @package  Classic Menu in Navigation Block
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Constants.
define( 'CMINB_VERSION', '1.0.0' );
define( 'CMINB_FILE', __FILE__ );
define( 'CMINB_PATH', plugin_dir_path( CMINB_FILE ) ); // Trailing slashed.
define( 'CMINB_URL', plugin_dir_url( CMINB_FILE ) ); // Trailing slashed.

// Load the functionality.
require_once CMINB_PATH . 'includes/Autoload.php';
WebManDesign\CMiNB\Load::init();

// @todo Test with empty Navigation block.
// @todo Test with Gwyneth site header nav.
// @todo Test with Polylang.
// @todo Test with WPML.
// @todo Localization files.
// @todo Test switching website to Slovak language if localization applies correctly.
// @todo Update readme.txt.
