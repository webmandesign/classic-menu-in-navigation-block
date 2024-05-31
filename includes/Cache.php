<?php
/**
 * Cache.
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

class Cache {

	/**
	 * Cache transient name.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     string
	 */
	public static $transient = 'classic_menu_in_navigation_block';

	/**
	 * Cache transient expiration in seconds.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     int
	 */
	public static $expiration = 14 * DAY_IN_SECONDS;

	/**
	 * Retrieves cached item content.
	 *
	 * @since  1.0.0
	 *
	 * @param  int $menu_id
	 *
	 * @return  array
	 */
	public static function get( int $menu_id ): array {

		// Output

			return array_filter( (array) get_transient( self::$transient . '--' . $menu_id ) );

	} // /get

	/**
	 * Saves the data to the cache.
	 *
	 * @since  1.0.0
	 *
	 * @param  int   $menu_id
	 * @param  array $data
	 *
	 * @return  void
	 */
	public static function set( int $menu_id, array $data ) {

		// Processing

			set_transient(
				self::$transient . '--' . $menu_id,
				array_filter( $data ),
				self::$expiration
			);

	} // /set

	/**
	 * Removes the cached item.
	 *
	 * @since  1.0.0
	 *
	 * @param  int $menu_id
	 *
	 * @return  void
	 */
	public static function delete( int $menu_id ) {

		// Processing

			delete_transient( self::$transient . '--' . $menu_id );

	} // /delete

	/**
	 * Is cache enabled?
	 *
	 * @since  1.0.3
	 *
	 * @return  bool
	 */
	public static function is_enabled(): bool {

		// Output

			return get_theme_mod( Options::$id['cache'], true );

	} // /is_enabled

}
