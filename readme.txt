=== wp-scroll-up ===
Contributors: dsawardekar
Donate link: http://pressing-matters.io/
Tags: back to top, scroll up, scroll to top
Requires at least: 3.5.0
Tested up to: 3.9
Stable tag: 0.5.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a Button to Scroll back to Top of any Post or Page in WordPress.

== Description ==

Adds a Scroll To Top button that allows visitors to scroll back to the
top of the page. You can customize the button style using predefined
styles or by adding a custom CSS file in your Theme.

== Installation ==

1. Click Plugins > Add New in the WordPress admin panel.
1. Search for "wp-scroll-up" and install.

### Customization ###

The *Style* dropdown changes the button style displayed. These
correspond to CSS styles present in the plugin's CSS directory.

When you choose the *Custom* style, the plugin automatically adds the
CSS file located at *{current_theme}/wp-scroll-up/custom.css*.

This CSS file must contain an ID selector, *#scrollUp* and it's custom
style attributes. For instance, the bundled `image` style is provided by the
CSS,

    /* Image style */
    #scrollUp {
      bottom: 20px;
      right: 20px;
      height: 38px; /* Height of image */
      width: 38px; /* Width of image */
      background: url(../img/top.png) no-repeat;
    }

Other settings that can be changed,

* Scroll Text: The text label of the button.
* Scroll Distance: The number of pixels that a user needs to scroll
  before they see the button.
* Scroll Speed: Duration in milliseconds to scroll to top.
* Animation: Whether the scroll button should Fade in/out

== Screenshots ==

1. Screenshot 1
2. Screenshot 2

== Credits ==

* Thanks to [Mark Goodyear](http://markgoodyear.com/) for [scrollUp](http://markgoodyear.com/2013/01/scrollup-jquery-plugin/).

== Upgrade Notice ==

* WP-Scroll-Up requires PHP 5.3.2+

== Changelog ==

= 0.5.1 =

* Upgrades to Arrow 1.8.0

= 0.5.0 =

* Upgrades to Arrow 1.6.0.
* Migrates options page to React.

= 0.4.0 =

* Upgrades to Arrow 0.7.0

= 0.3.0 =

* Upgrades to Arrow 0.5.1.

= 0.2.2 =

* Fixes typos.

= 0.2.1 =

* Upgrades Arrow to 0.4.1.

= 0.2.0 =

* Switched to Arrow 0.4.0.

= 0.1.3 =

* First release on wordpress.org

= 0.1.2 =

* Updates Encase-php to 0.1.3.

= 0.1.1 =

* Adds readme.

= 0.1.0 =

* Initial Release
