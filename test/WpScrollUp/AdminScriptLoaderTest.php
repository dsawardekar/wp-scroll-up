<?php

namespace WpScrollUp;

use Encase\Container;

class AdminScriptLoaderTest extends \WP_UnitTestCase {

  public $container;
  public $loader;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->factory('script', 'WpScrollUp\\Script');
    $this->container->object('pluginFile', getcwd() .  "/wp-scroll-up.php");
    $this->container->object('pluginSlug', 'wp_scroll_up');
    $this->container->singleton('loader', 'WpScrollUp\\AdminScriptLoader');

    $this->loader = $this->container->lookup('loader');
  }

  function test_it_has_admin_enqueue_action() {
    $actual = $this->loader->enqueueAction();
    $this->assertEquals('admin_enqueue_scripts', $actual);
  }

  function test_it_can_enqueue_admin_scripts() {
    $this->loader->schedule('foo');
    $this->loader->load();
    $this->loader->load();

    // TODO: how to test if admin script is enqueued
    $this->assertTrue(wp_script_is('foo', 'registered'));
  }

}
