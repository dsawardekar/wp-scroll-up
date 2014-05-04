<?php
/*
Plugin Name: wp-scroll-up
Description: WordPress Integration with the scrollUp jQuery Plugin.
Version: 0.1.5
Author: Darshan Sawardekar
Author URI: http://pressing-matters.io/
Plugin URI: http://wordpress.org/plugins/wp-scroll-up
License: GPLv2
*/

require_once(__DIR__ . '/vendor/autoload.php');

use WpScrollUp\Plugin;

$wp_scroll_up_plugin = Plugin::create(__FILE__);
$wp_scroll_up_plugin->enable();
