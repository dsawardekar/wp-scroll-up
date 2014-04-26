<?php

namespace WpScrollUp;

use Encase\Container;

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

    $this->container = $container;
  }

  function lookup($key) {
    return $this->container->lookup($key);
  }

}
