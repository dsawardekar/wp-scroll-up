<?php

namespace WpScrollUp;

use Encase\Container;

class StylesheetText extends \WP_UnitTestCase {

  public $container;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->singleton('stylesheet', 'WpScrollUp\\Stylesheet');
    $this->container->object('pluginFile', getcwd() .  '/wp-scroll-up.php');

    $this->stylesheet = $this->container->lookup('stylesheet');
  }

  function test_it_has_css_dirname() {
    $actual = $this->stylesheet->dirname();
    $this->assertEquals('css', $actual);
  }

  function test_it_has_css_extension() {
    $actual = $this->stylesheet->extension();
    $this->assertEquals('.css', $actual);
  }

  function test_it_can_register_style() {
    $this->stylesheet->slug = 'foo';
    $this->stylesheet->register();
    $this->assertTrue(wp_style_is('foo', 'registered'));
  }

  function test_it_can_enqueue_style() {
    $this->stylesheet->slug = 'foo';
    $this->stylesheet->register();
    $this->stylesheet->enqueue();

    $this->assertTrue(wp_style_is('foo', 'enqueued'));
  }
}
