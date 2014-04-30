<?php

namespace WpScrollUp;

class PluginTest extends \WP_UnitTestCase {

  public $plugin;
  public $pluginFile;

  function setUp() {
    parent::setUp();

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

  function test_it_contains_plugin_slug() {
    $slug = $this->plugin->lookup('pluginSlug');
    $this->assertEquals('wp_scroll_up', $slug);
  }

  function test_it_contains_an_option_store() {
    $optionStore = $this->plugin->lookup('optionStore');
    $this->assertInstanceOf('WpScrollUp\\OptionStore', $optionStore);
  }

  function test_it_contains_an_option_sanitizer() {
    $optionSanitizer = $this->plugin->lookup('optionSanitizer');
    $this->assertInstanceOf('WpScrollUp\\OptionSanitizer', $optionSanitizer);
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

  function test_it_contains_script_loader() {
    $loader = $this->plugin->lookup('scriptLoader');
    $this->assertInstanceOf('WpScrollUp\\ScriptLoader', $loader);
  }

  function test_it_contains_stylesheet_loader() {
    $loader = $this->plugin->lookup('stylesheetLoader');
    $this->assertInstanceOf('WpScrollUp\\StylesheetLoader', $loader);
  }

  function test_it_contains_option_page() {
    $page = $this->plugin->lookup('optionPage');
    $this->assertInstanceOf('WpScrollUp\\OptionPage', $page);
  }

  function test_it_can_get_dir_for_plugin_file() {
    $file = getcwd() . '/wp-scroll-up.php';
    $dir = $this->plugin->toPluginDir($file);
    $dirname = basename(getcwd());
    $this->assertStringEndsWith($dirname, $dir);
  }

  function test_it_can_initialize_option_store() {
    $this->plugin->enable();
    $this->plugin->initOptionStore();
    // todo how to test if setting was registered?
  }

  function test_it_has_default_options() {
    $actual = $this->plugin->getDefaultOptions();
    $this->assertEquals('fade', $actual['animation']);
  }

  function test_it_can_build_theme_stylesheet() {
    $optionStore = $this->plugin->lookup('optionStore');
    $optionStore->load();
    $optionStore->setOption('style', 'foo');
    $actual = $this->plugin->getThemeStylesheet();

    $this->assertEquals('jquery-scroll-up-foo', $actual);
  }

  function test_it_can_build_custom_theme_stylesheet() {
    $optionStore = $this->plugin->lookup('optionStore');
    $optionStore->load();
    $optionStore->setOption('style', 'custom');
    $actual = $this->plugin->getThemeStylesheet();

    $this->assertEquals('theme-custom', $actual);
  }

  function test_it_can_fetch_scroll_up_options() {
    $optionStore = $this->plugin->lookup('optionStore');
    $optionStore->defaultOptions = $this->plugin->getDefaultOptions();
    $optionStore->load();
    $actual = $this->plugin->getScrollUpOptions('foo');

    $this->assertEquals('Scroll To Top', $actual['scrollText']);
  }

  function test_it_does_not_add_scroll_text_for_image_style() {
    $optionStore = $this->plugin->lookup('optionStore');
    $optionStore->defaultOptions = $this->plugin->getDefaultOptions();
    $optionStore->load();
    $optionStore->setOption('style', 'image');
    $actual = $this->plugin->getScrollUpOptions('foo');

    $this->assertEquals('', $actual['scrollText']);
  }

}
