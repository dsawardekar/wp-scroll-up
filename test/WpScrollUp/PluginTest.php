<?php

namespace WpScrollUp;

class PluginTest extends \PHPUnit_Framework_TestCase {

  public $plugin;
  public $pluginFile;

  function setUp() {
    $this->pluginFile = getcwd() . '/wp-scroll-up.php';
    $this->plugin = new Plugin($this->pluginFile);
  }

  function test_it_can_be_statically_created() {
    $instance = Plugin::create($this->pluginFile);
    $this->assertInstanceOf('WpScrollUp\\Plugin', $instance);
  }

  function test_it_does_not_recreate_new_instances() {
    $instance1 = Plugin::create($this->pluginFile);
    $instance2 = Plugin::create($this->pluginFile);

    $this->assertEquals($instance1, $instance2);
  }

  function test_it_stores_singleton_instance() {
    $instance1 = Plugin::create($this->pluginFile);
    $instance2 = Plugin::getInstance();

    $this->assertEquals($instance1, $instance2);
  }

  function test_it_has_a_container() {
    $container = $this->plugin->container;
    $this->assertInstanceOf('Encase\\Container', $container);
  }

  function test_it_contains_plugin_file() {
    $pluginFile = $this->plugin->lookup('pluginFile');
    $this->assertEquals($this->pluginFile, $pluginFile);
  }

  function test_it_contains_plugin_dir() {
    $dir = $this->plugin->lookup('pluginDir');
    $this->assertEquals(getcwd(), $dir);
  }

  function test_it_contains_a_twig_helper() {
    $helper = $this->plugin->lookup('twigHelper');
    $this->assertInstanceOf('WordPress\\TwigHelper', $helper);
  }

  function test_it_configures_twig_helper_when_enabled() {
    $helper = $this->plugin->lookup('twigHelper');
    $this->plugin->enable();

    $this->assertEquals(getcwd(), $helper->getBaseDir());
  }

}
