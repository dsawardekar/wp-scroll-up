<?php

namespace WpScrollUp;

use Encase\Container;

class MockSanitizer {
  function sanitize($options) {
    return $options;
  }

  function hasErrors() {
    return $this->mockHasErrors;
  }

  function getErrors() {
    return $this->mockErrors;
  }
}

class OptionStoreTest extends \WP_UnitTestCase {

  public $container;
  public $store;

  function setUp() {
    parent::setUp();
    $this->container = new Container();
    $this->container->singleton('optionStore', 'WpScrollUp\\OptionStore');
    $this->container->singleton('optionSanitizer', 'WpScrollUp\\MockSanitizer');
    $this->container->object('pluginSlug', 'wp_scroll_up');
    $this->container->object('optionName', 'wp_scroll_up_options');
    $this->container->object('defaultOptions', array(
      'scrollText' => 'Scroll To Top'
    ));

    $this->store = $this->container->lookup('optionStore');
  }

  function test_it_has_an_option_name() {
    $optionName = $this->store->getOptionName();
    $this->assertEquals('wp_scroll_up_options', $optionName);
  }

  function test_it_has_option_defaults() {
    $defaults = array('scrollText' => 'Scroll To Top');
    $this->assertEquals($defaults, $this->store->getDefaultOptions());
  }

  function test_it_has_a_sanitizer() {
    $sanitizer = $this->container->lookup('optionSanitizer');
    $actual = $this->store->getOptionSanitizer();
    $this->assertEquals($sanitizer, $actual);
  }

  function test_it_can_convert_options_to_json() {
    $options = array( 'foo' => 'bar' );
    $json = $this->store->toJSON($options);

    $this->assertEquals('{"foo":"bar"}', $json);
  }

  function test_it_can_convert_json_to_options() {
    $json = '{"foo": "bar"}';
    $options = $this->store->toOptions($json);

    $this->assertEquals(array('foo' => 'bar'), $options);
  }

  function test_it_can_parse_valid_json() {
    $json = '{"foo": "bar"}';
    $options = $this->store->parse($json);
    $this->assertEquals(array('foo' => 'bar'), $options);
  }

  function test_it_returns_default_options_for_false_json() {
    $options = $this->store->parse(false);
    $this->assertTrue(array_key_exists('scrollText', $options));
  }

  function test_it_returns_defaults_options_for_invalid_json() {
    $json = '{"foo": "bar}';
    $options = $this->store->parse($json);
    $this->assertFalse(array_key_exists('foo', $options));
  }

  function test_it_knows_if_options_have_loaded() {
    $loaded = $this->store->loaded();
    $this->assertFalse($loaded);
  }

  function test_it_can_load_options_from_database() {
    update_option('wp_scroll_up_options', '{"a": "b"}');
    $options = $this->store->load();

    $this->assertEquals(array('a' => 'b'), $options);
  }

  function test_it_uses_default_options_if_absent_from_database() {
    delete_option('wp_scroll_up_options');
    $options = $this->store->load();
    $this->assertTrue(array_key_exists('scrollText', $options));
  }

  function test_it_can_get_all_options() {
    update_option('wp_scroll_up_options', '{"a": "b", "c":"d"}');
    $options = $this->store->getOptions();

    $this->assertEquals(array('a' => 'b', 'c' => 'd'), $options);
  }

  function test_it_can_get_single_option() {
    update_option('wp_scroll_up_options', '{"a": "b", "c":"d"}');
    $a = $this->store->getOption('a');

    $this->assertEquals('b', $a);
  }

  function test_it_returns_default_option_if_absent_in_database() {
    $this->store->clear();
    $actual = $this->store->getOption('scrollText');
    $this->assertEquals('Scroll To Top', $actual);
  }

  function test_it_can_clear_all_options() {
    $this->store->clear();
    $this->assertFalse(get_option('wp_scroll_up_options'));
  }

  function test_it_can_register_option_with_wordpress() {
    $this->store->register();
    $this->assertTrue(has_action('sanitize_option_wp_scroll_up_options'));
  }

  function test_it_can_sanitize_options_from_user() {
    $options   = array( 'a' => 1 );
    $sanitizer = $this->container->lookup('optionSanitizer');
    $sanitizer->mockHasErrors = false;
    $actual    = json_decode($this->store->sanitize($options), true);

    $this->assertEquals($options, $actual);
  }

  function test_it_knows_if_already_sanitized() {
    $options   = array( 'a' => 2 );
    $sanitizer = $this->container->lookup('optionSanitizer');
    $sanitizer->mockHasErrors = false;
    $json    = $this->store->sanitize($options);

    $this->assertTrue($this->store->isSanitized($json));
  }

  function test_it_always_sanitizes_array_of_options() {
    $options   = array( 'a' => 2 );
    $sanitizer = $this->container->lookup('optionSanitizer');
    $sanitizer->mockHasErrors = false;
    $json1    = $this->store->sanitize(array('a' => 1));
    $json2    = $this->store->sanitize(array('a' => 2));

    $this->assertNotEquals($json1, $json2);
  }

  function test_it_can_notify_sanitization_errors_to_wordpress() {
    $sanitizer = $this->container->lookup('optionSanitizer');
    $sanitizer->mockHasErrors = true;
    $sanitizer->mockErrors = array(
      (object) array('message' => 'foo'),
      (object) array('message' => 'bar')
    );

    $this->store->sanitize(array('a' => 1));
    $errors = get_settings_errors();

    $this->assertEquals('foo', $errors[0]['message']);
    $this->assertEquals('bar', $errors[1]['message']);
  }

  function test_it_can_reload_options() {
    $this->store->defaultOptions = array('a' => 0, 'b' => 0);
    $this->store->load();

    update_option('wp_scroll_up_options', '{"a":5,"b":6}');
    $this->store->reload();

    $this->assertEquals(5, $this->store->getOption('a'));
    $this->assertEquals(6, $this->store->getOption('b'));
  }

  function test_it_can_change_and_save_options() {
    update_option('wp_scroll_up_options', '{"a":1, "b":2}');
    $this->store->defaultOptions = array('a' => 0, 'b' => 0);
    $this->store->load();

    $this->store->setOption('a', 10);
    $this->store->setOption('b', 20);

    $this->store->save();
    $this->store->reload();

    $this->assertEquals(10, $this->store->getOption('a'));
    $this->assertEquals(20, $this->store->getOption('b'));
  }
}
