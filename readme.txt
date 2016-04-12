=== Rehabtabs ===
Contributors: vancoder
Author URI: http://vancoder.ca/
Plugin URI: http://vancoder.ca/plugins/rehabtabs
Tags: tabs, jQuery UI
Requires at least: 3.2.1
Tested up to: 3.4.1
Stable tag: 1.1.2

Rehabtabs makes it easy to add pretty jQuery UI tabs to your pages, posts and custom posts using simple shortcodes.

== Description ==

There are several tabs plugins available, but many of them are poorly coded or use non-intuitive shortcode implementations.

Rehabtabs is intended to to be a clean and intuitive alternative. It facilitates the application of jQuery UI tabs to your pages, posts and custom posts.

Rehabtabs currently supports:

* interchangeable jQuery UI themes (some included)
* Ajax mode
* cookie persistence
* collapsible tabs
* fx options

In common with all Vancoder plugins, Rehabtabs strives to follow best practice in WordPress coding. If you spy a bug or see room for improvement, please [let me know](http://wordpress.org/tags/rehabtabs?forum_id=10).

== Installation ==

1. Upload `rehabtabs` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit the Settings page to change defaults, if required

== Frequently Asked Questions ==

= How do I use other jQuery UI themes? =

1. Go to the [jQuery ThemeRoller](http://jqueryui.com/themeroller/)
1. Choose your theme and click the download button
1. Deselect all components, then reselect Tabs (under Widgets)
1. Download, giving you a directory called jquery-ui-x.x.xx.custom
1. Open the subdirectory called css, and copy your chosen theme folder
1. Paste this folder into plugins/rehabtabs/themes
1. Your theme should now be available on the Settings page

== Changelog ==

= 1.1.2 =
* Minor bug fix

= 1.1.1 =
* Minor bug fix

= 1.1 =
* Added usage section to settings
* Added jQuery UI spinner option to settings
* Fixed some notices

= 1.0 =
* Modified to accommodate older PHP versions

= 0.1 =
* Initial release