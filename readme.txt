=== Classic Menu in Navigation Block ===

Contributors:      webmandesign
Donate link:       https://www.webmandesign.eu/contact/#donation
Author URI:        https://www.webmandesign.eu
Plugin URI:        https://www.webmandesign.eu/portfolio/classic-menu-in-navigation-block-wordpress-plugin/
Requires at least: 6.2
Tested up to:      6.7
Requires PHP:      7.0
Stable tag:        1.0.4
License:           GPL-3.0-or-later
License URI:       http://www.gnu.org/licenses/gpl-3.0.html
Tags:              block, navigation, menu, classic, multilingual

Extending WordPress Navigation block with functionality to display classic menus.


== Description ==

Extending WordPress Navigation block with functionality to display classic menus.

= What problem does it solve? =

❓ _Do you experience problems making your website navigation multilingual when using block theme (FSE - full site editing)?_
❓ _Are you using a plugin that modifies classic menu but not Navigation block and you are using block theme?_

**Classic Menu in Navigation Block** plugin lets you display classic menus in Navigation block so you can gain from using classic menu modification plugins and yet display the menu with accessibility, customization and responsive features of Navigation block.

This method is particularly useful when building a multilingual website and your multilingual plugin does not handle Navigation block very well. You can simply "revert" back to using classic menu, which multilingual plugins supports out of the box.

You can do changes to your classic menus and Navigation block will always display up to date version of the menu.

To speed things up the plugin also applies caching for your classic menus converted to blocks. This cache is updated every time you update your classic menu. If you are using a very dynamic classic menu modification plugin, you can even disable the cache.

= Got a question or suggestion? =

In case of any question or suggestion regarding this plugin, feel free to ask at [support section](https://wordpress.org/support/plugin/classic-menu-in-navigation-block/), or at [GitHub repository issues](https://github.com/webmandesign/classic-menu-in-navigation-block/issues).


== Installation ==

1. Unzip the plugin download file and upload `classic-menu-in-navigation-block` folder into the `/wp-content/plugins/` directory.
2. Activate the plugin through the *"Plugins"* menu in WordPress.
3. Plugin works immediately after activation by adding a new settings options to WordPress native _**Navigation** block_ in block and site editor. It also enables **Appearance → Menus** admin screen for block themes (FSE - full site editing) and registers several menu locations.


== Frequently Asked Questions ==

= How does it work? =

1. In **Appearance → Menus** create a classic menu and assign it to a menu location.
2. In site editor (**Appearance → Edit**) modify your Navigation block to display the menu location.
3. Preview the results on front-end of your website. Your Navigation block now displays your classic menu and yet it keeps all its functionality.

= My menu experiences issues! =

Depending on your multilingual plugin, you may experience some issues with your menus. Such as multiple current (active) menu items. To resolve these issues, try to disable block menu cache in **Appearance → Menus → Classic Menu in Navigation Block**.

= Can I disable cache? =

Yes, you can do so in **Appearance → Menus → Classic Menu in Navigation Block**. This is helpful when your multilingual plugin renders classic menus dynamically.

= Does it work with Polylang plugin? =

Yes.

= Does it work with WPML plugin? =

[WPML can translate Site Editor and navigation block](https://wpml.org/documentation/getting-started-guide/translating-content-created-using-gutenberg-editor/translating-sites-that-use-full-site-editing/), so there is no need to use this plugin with WPML.

But if you decide to use **Classic Menu in Navigation Block** with WPML, make sure to disable the block menu cache (**Appearance → Menus → Classic Menu in Navigation Block → "Block menu cache"**), and translate the modified block theme templates and/or template parts (**WPML → Translation Management**, or check the WPML documentation for more info).

= Where are plugin options? =

You can find them at **Appearance → Menus → Classic Menu in Navigation Block**.


== Screenshots ==

1. Setting up classic menu displaying in Navigation block
2. Creating a classic menu
3. Preview of a classic menu rendered by Navigation block
4. Functionality of Navigation block is kept


== Changelog ==

Please see the [`changelog.md` file](https://github.com/webmandesign/classic-menu-in-navigation-block/blob/master/changelog.md) for details.


== Upgrade Notice ==

= 1.0.3 =
Adding plugin options, visualizing modified Navigation block in editor.

= 1.0.2 =
Fixing PHP error in specific cases.

= 1.0.1 =
Making the plugin work with different language menu locations.

= 1.0.0 =
Initial release.
