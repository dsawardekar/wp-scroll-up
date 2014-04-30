<?php

namespace WpScrollUp;

use WpScrollUp\Script;
use Encase\Container;

class ScriptTest extends \WP_UnitTestCase {

  public $container;

  function setUp() {
    parent::setUp();

    $this->container = new Container();
    $this->container->singleton('script', 'WpScrollUp\\Script');
    $this->container->object('pluginFile', getcwd() .  '/wp-scroll-up.php');
    $this->container->object('pluginSlug', 'wp_scroll_up');

    $this->script = $this->container->lookup('script');
  }

  function test_it_has_js_dirname() {
    $this->assertEquals('js', $this->script->dirname());
  }

  function test_it_has_js_extension() {
    $this->assertEquals('.js', $this->script->extension());
  }

  function test_it_can_register_script() {
    $this->script->slug = 'foo';
    $this->script->register();
    $this->assertTrue(wp_script_is('foo', 'registered'));
  }

  function test_it_runs_localizer_if_present() {
    $this->script->slug = 'foo';
    $this->script->localizer = array($this, 'onLocalize');
    $this->script->register();
    $this->assertTrue($this->script->localized);
  }

  function onLocalize($script) {
    return array();
  }

  function test_it_can_enqueue_script() {
    $this->script->slug = 'foo';
    $this->script->register();
    $this->script->enqueue();
    $this->assertTrue(wp_script_is('foo', 'enqueued'));
  }

}
