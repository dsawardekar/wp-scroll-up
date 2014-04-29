<?php

namespace WpScrollUp;

use Encase\Container;
use WordPress\TwigHelper;

class Plugin {

  static $instance = null;
  static function create($file) {
    if (is_null(self::$instance)) {
      self::$instance = new Plugin($file);
    }

    return self::$instance;
  }

  static function getInstance() {
    return self::$instance;
  }

  public $container;

  function __construct($file) {
    $container = new Container();
    $container->object('pluginFile', $file);
    $container->object('pluginDir', $this->toPluginDir($file));
    $container->object('pluginSlug', 'wp_scroll_up');

    $container->singleton('twigHelper', 'WordPress\\TwigHelper');
    $container->singleton('optionStore', 'WpScrollUp\\OptionStore');
    $container->singleton('optionSanitizer', 'WpScrollUp\\OptionSanitizer');
    $container->singleton('optionPage', 'WpScrollUp\\OptionPage');

    $this->container = $container;
  }

  function lookup($key) {
    return $this->container->lookup($key);
  }

  function enable() {
    $twigHelper = $this->lookup('twigHelper');
    $twigHelper->setBaseDir($this->lookup('pluginDir'));

    add_action('admin_init', array($this, 'initOptionStore'));
    add_action('admin_menu', array($this, 'initOptionPage'));
  }

  function toPluginDir($file) {
    return untrailingslashit(plugin_dir_path($file));
  }

  function initOptionStore() {
    $optionStore = $this->lookup('optionStore');
    $optionStore->setDefaults($this->getDefaultOptions());
    $optionStore->setPluginSlug($this->lookup('pluginSlug'));
    $optionStore->setOptionName($this->lookup('pluginSlug') .  '_options');
    $optionStore->setSanitizer($this->lookup('optionSanitizer'));

    $optionStore->register();
  }

  function initOptionPage() {
    $this->lookup('optionPage')->register();
  }

  function getDefaultOptions() {
    return array(
      'scrollDistance' => 300,
      'scrollSpeed'    => 300,
      'animation'      => 'fade',
      'animationSpeed' => '100',
      'scrollText'     => 'Scroll To Top',
      'scrollImage'    => '',
      'style'          => 'tab'
    );
  }

}
