<?php
/*
Plugin Name: wp-scroll-up
Description: WordPress Integration with the scrollUp jQuery Plugin.
Version: 0.5.1
Author: Darshan Sawardekar
Author URI: http://pressing-matters.io/
Plugin URI: http://wordpress.org/plugins/wp-scroll-up
License: GPLv2
*/

require_once(__DIR__ . '/vendor/dsawardekar/arrow/lib/Arrow/ArrowPluginLoader.php');

function wp_scroll_up_main() {
  $options = array(
    'plugin' => 'WpScrollUp\Plugin',
    'arrowVersion' => '1.8.0'
  );

  ArrowPluginLoader::load(__FILE__, $options);
}

wp_scroll_up_main();
