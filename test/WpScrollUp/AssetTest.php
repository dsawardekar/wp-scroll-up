<?php

namespace WpScrollUp;

use Encase\Container;
use WpScrollUp\Asset;

class AssetTest extends \WP_UnitTestCase {

  public $container;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->singleton('asset', 'WpScrollUp\\Asset');
    $this->container->object('pluginFile', getcwd() .  '/wp-scroll-up.php');
    $this->container->object('pluginSlug', 'wp_scroll_up');

    $this->asset = $this->container->lookup('asset');
  }

  function test_it_has_a_container() {
    $this->assertInstanceOf('Encase\\Container', $this->asset->container);
  }

  function test_it_can_build_relative_path() {
    $this->asset->slug = 'foo';
    $path = $this->asset->relpath();

    $this->assertEquals('assets/foo.js', $path);
  }

  function test_it_can_build_path_to_plugin_asset() {
    $this->asset->slug = 'foo';
    $parent = dirname($this->container->lookup('pluginFile'));
    $expected = $parent . '/assets/foo.js';
    $actual = $this->asset->path();

    $this->assertStringEndsWith($expected, $actual);
  }

  function test_it_can_store_options() {
    $this->asset->options = array('in_footer' => true);
    $this->assertTrue($this->asset->options['in_footer']);
  }

  function test_it_can_build_localize_slug_name() {
    $this->asset->slug = 'wp-scroll-up';
    $actual = $this->asset->localizeSlug();
    $this->assertEquals('wp_scroll_up', $actual);
  }

  function test_it_can_run_localizer() {
    $this->asset->slug = 'foo';
    $this->asset->localizer = array($this, 'onLocalize');
    $result = $this->asset->runLocalizer();
    $this->assertEquals(array('foo' => 'bar'), $result);
  }

  function onLocalize($asset) {
    return array('foo' => 'bar');
  }

  function test_it_can_detect_a_custom_slug() {
    $this->asset->slug = 'theme-custom';
    $this->assertTrue($this->asset->isCustomSlug());
  }

  function test_it_can_detect_normal_slug() {
    $this->asset->slug = 'wp-scroll-up-foo';
    $this->assertFalse($this->asset->isCustomSlug());
  }

  function test_it_can_build_custom_path() {
    $this->asset->slug = 'theme-foo';
    $expected = 'wp-scroll-up/foo.js';
    $this->assertStringEndsWith($expected, $this->asset->customPath());
  }

}
