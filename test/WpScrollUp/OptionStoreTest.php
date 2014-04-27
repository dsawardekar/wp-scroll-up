<?php

namespace WpScrollUp;

use Encase\Container;

class MockSanitizer {
  function sanitize($options) {
    return $options;
  }
}

class OptionStoreTest extends \WP_UnitTestCase {

  public $container;
  public $optionStore;

  function setUp() {
    parent::setUp();
    $this->container = new Container();
    $this->container->singleton('optionStore', 'WpScrollUp\\OptionStore');
    $this->container->singleton('optionSanitizer', 'WpScrollUp\\MockSanitizer');
    $this->container->object('pluginSlug', 'wp-scroll-up');

    $this->optionStore = $this->container->lookup('optionStore');
  }

  function test_it_has_a_plugin_slug() {
    $slug = $this->optionStore->pluginSlug;
    $this->assertEquals('wp-scroll-up', $slug);
  }

  function test_it_has_group_name() {
    $groupName = $this->optionStore->getGroupName();
    $this->assertEquals('wp-scroll-up-option-store', $groupName);
  }

  function test_it_has_setting_name() {
    $settingName = $this->optionStore->getSettingName();
    $this->assertEquals('wp-scroll-up-options', $settingName);
  }

  function test_it_can_convert_options_to_json() {
    $options = array( 'foo' => 'bar' );
    $json = $this->optionStore->toJSON($options);

    $this->assertEquals('{"foo":"bar"}', $json);
  }

  function test_it_can_convert_json_to_options() {
    $json = '{"foo": "bar"}';
    $options = $this->optionStore->toOptions($json);

    $this->assertEquals(array('foo' => 'bar'), $options);
  }

  function test_it_knows_if_options_have_loaded() {
    $loaded = $this->optionStore->loaded();
    $this->assertFalse($loaded);
  }

  function test_it_can_load_options_from_database() {
    update_option('wp-scroll-up-options', '{"a": "b"}');
    $options = $this->optionStore->load();

    $this->assertEquals(array('a' => 'b'), $options);
  }

  function test_it_uses_default_options_if_absent() {
    delete_option('wp-scroll-up-options');
    $options = $this->optionStore->load();
    $this->assertEquals(array('foo' => 'bar'), $options);
  }

  function test_it_can_get_all_options() {
    update_option('wp-scroll-up-options', '{"a": "b", "c":"d"}');
    $options = $this->optionStore->getOptions();

    $this->assertEquals(array('a' => 'b', 'c' => 'd'), $options);
  }

  function test_it_can_get_single_option() {
    update_option('wp-scroll-up-options', '{"a": "b", "c":"d"}');
    $a = $this->optionStore->getOption('a');

    $this->assertEquals('b', $a);
  }

  function test_it_returns_default_option_if_absent_in_database() {
    $this->optionStore->clear();
    $actual = $this->optionStore->getOption('foo');
    $this->assertEquals('bar', $actual);
  }

  function test_it_can_clear_all_options() {
    $this->optionStore->clear();
    $this->assertFalse($this->optionStore->loaded());
  }

  function test_it_can_register_option_with_wordpress() {
    $this->optionStore->register();
    $this->assertTrue(has_action('sanitize_option_wp-scroll-up-options'));
  }

  function test_it_can_sanitize_options_from_user() {
    $options   = array( 'a' => 1 );
    $sanitizer = $this->container->lookup('optionSanitizer');
    $actual    = $this->optionStore->sanitize($options);
    $expected  = array('wp-scroll-up-options' => '{"a":1}');

    $this->assertEquals($expected, $actual);
  }
}
