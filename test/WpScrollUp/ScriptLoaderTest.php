<?php

namespace WpScrollUp;

use WpScrollUp\AssetLoader;
use Encase\Container;

class ScriptLoaderTest extends \WP_UnitTestCase {

  public $container;
  public $loader;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->factory('script', 'WpScrollUp\\Script');
    $this->container->object('pluginFile', getcwd() .  "/wp-scroll-up.php");
    $this->container->singleton('loader', 'WpScrollUp\\ScriptLoader');

    $this->loader = $this->container->lookup('loader');
  }

  function test_it_has_script_asset_type() {
    $this->assertEquals('script', $this->loader->assetType());
  }

  function test_it_can_enqueue_scripts() {
    $this->loader->schedule('foo');
    $this->loader->register();
    $this->loader->enqueue();

    do_action('wp_enqueue_scripts');

    $this->assertTrue(wp_script_is('foo', 'enqueued'));
  }

}
