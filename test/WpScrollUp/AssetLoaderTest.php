<?php

namespace WpScrollUp;

use Encase\Container;

class AssetLoaderTest extends \WP_UnitTestCase {

  public $container;
  public $loader;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->factory('asset', 'WpScrollUp\\Script');
    $this->container->object('pluginFile', getcwd() .  "/wp-scroll-up.php");
    $this->container->singleton('loader', 'WpScrollUp\\AssetLoader');

    $this->loader = $this->container->lookup('loader');
  }

  function test_it_has_a_container() {
    $this->assertInstanceOf('Encase\\Container', $this->loader->container);
  }

  function test_it_is_not_loaded_initially() {
    $this->assertFalse($this->loader->loaded());
  }

  function test_it_can_schedule_asset_for_loading() {
    $this->loader->schedule('foo');
    $script = $this->loader->find('foo');
    $this->assertEquals('foo', $script->slug);
  }

  function test_it_knows_if_asset_is_scheduled() {
    $this->loader->schedule('foo');
    $this->assertTrue($this->loader->isScheduled('foo'));
  }

  function test_it_knows_if_asset_is_not_scheduled() {
    $this->assertFalse($this->loader->isScheduled('foo'));
  }

  function test_it_can_schedule_asset_with_options() {
    $this->loader->schedule('foo', array('in_footer' => true));
    $script = $this->loader->find('foo');
    $this->assertTrue($script->options['in_footer']);
  }

  function test_it_can_add_dependencies_to_assets() {
    $this->loader->schedule('foo');
    $this->loader->dependency('foo', array('jquery', 'jquery-ui'));
    $script = $this->loader->find('foo');

    $this->assertEquals(array('jquery', 'jquery-ui'), $script->dependencies);
  }

  function test_it_can_register_scripts() {
    $this->loader->schedule('foo');
    $this->loader->register();

    $this->assertTrue(wp_script_is('foo', 'registered'));
  }

  function test_it_can_enqueue_scripts() {
    $this->loader->schedule('foo');
    $this->loader->register();
    $this->loader->doEnqueue();

    $this->assertTrue(wp_script_is('foo', 'enqueued'));
  }

}
