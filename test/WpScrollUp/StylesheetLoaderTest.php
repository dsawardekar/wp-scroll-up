<?php

namespace WpScrollUp;

use WpScrollUp\AssetLoader;
use Encase\Container;

class StylesheetLoaderTest extends \WP_UnitTestCase {

  public $container;
  public $loader;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->factory('stylesheet', 'WpScrollUp\\Stylesheet');
    $this->container->object('pluginFile', getcwd() .  "/wp-scroll-up.php");
    $this->container->object('pluginSlug', 'wp_scroll_up');
    $this->container->singleton('loader', 'WpScrollUp\\StylesheetLoader');

    $this->loader = $this->container->lookup('loader');
  }

  function test_it_has_stylesheet_asset_type() {
    $this->assertEquals('stylesheet', $this->loader->assetType());
  }

  function test_it_can_enqueue_stylesheets() {
    $this->loader->schedule('foo');
    $this->loader->register();
    $this->loader->enqueue();

    do_action('wp_enqueue_scripts');

    $this->assertTrue(wp_style_is('foo', 'enqueued'));
  }

}
