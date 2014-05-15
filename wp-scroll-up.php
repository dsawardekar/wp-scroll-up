<?php
/*
Plugin Name: wp-scroll-up
Description: WordPress Integration with the scrollUp jQuery Plugin.
Version: 0.2.2
Author: Darshan Sawardekar
Author URI: http://pressing-matters.io/
Plugin URI: http://wordpress.org/plugins/wp-scroll-up
License: GPLv2
*/

require_once(__DIR__ . '/vendor/dsawardekar/wp-requirements/lib/Requirements.php');

function wp_scroll_up_main() {
  $requirements = new WP_Requirements();

  if ($requirements->satisfied()) {
    wp_scroll_up_register();
  } else {
    $plugin = new WP_Faux_Plugin('WP Scroll Up', $requirements->getResults());
    $plugin->activate(__FILE__);
  }
}

function wp_scroll_up_register() {
  require_once(__DIR__ . '/vendor/dsawardekar/arrow/lib/Arrow/ArrowPluginLoader.php');

  $loader = ArrowPluginLoader::getInstance();
  $loader->register(__FILE__, '0.5.1', 'wp_scroll_up_load');
}

function wp_scroll_up_load() {
  $plugin = \WpScrollUp\Plugin::create(__FILE__);
  $plugin->enable();
}

wp_scroll_up_main();
