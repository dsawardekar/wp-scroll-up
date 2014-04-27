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
    $container->object('pluginSlug', 'wp-scroll-up');

    $container->singleton('twigHelper', 'WordPress\\TwigHelper');
    $container->singleton('optionStore', 'WpScrollUp\\OptionStore');
    $container->singleton('optionSanitizer', 'WpScrollUp\\OptionSanitizer');

    $this->container = $container;
  }

  function lookup($key) {
    return $this->container->lookup($key);
  }

  function enable() {
    $twigHelper = $this->lookup('twigHelper');
    $twigHelper->setBaseDir($this->lookup('pluginDir'));
  }

  function toPluginDir($file) {
    return untrailingslashit(plugin_dir_path($file));
  }

}
