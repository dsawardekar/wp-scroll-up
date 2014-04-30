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

    // plugin paths & defaults
    $container->object('pluginFile', $file);
    $container->object('pluginDir', $this->toPluginDir($file));
    $container->object('pluginSlug', 'wp_scroll_up');
    $container->object('optionName', 'wp_scroll_up_options');
    $container->object('defaultOptions', $this->getDefaultOptions());

    // asset loader
    $container->factory('script', 'WpScrollUp\\Script');
    $container->factory('stylesheet', 'WpScrollUp\\Stylesheet');
    $container->singleton('scriptLoader', 'WpScrollUp\\ScriptLoader');
    $container->singleton('stylesheetLoader', 'WpScrollUp\\StylesheetLoader');

    // twig
    $container->singleton('twigHelper', 'WordPress\\TwigHelper');

    // plugin options
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
    add_action('init', array($this, 'initPlugin'));
  }

  function toPluginDir($file) {
    return untrailingslashit(plugin_dir_path($file));
  }

  function initOptionStore() {
    $this->lookup('optionStore')->register();
  }

  function initOptionPage() {
    $this->lookup('optionPage')->register();
  }

  function initPlugin() {
    $this->initFrontEndScripts();
    $this->initFrontEndStyles();
  }

  function initFrontEndScripts() {
    $options = array(
      'version' => Version::$version,
      'in_footer' => true
    );

    $loader = $this->lookup('scriptLoader');
    $loader->schedule('jquery-scroll-up', $options);
    $loader->schedule('jquery-scroll-up-run', $options);

    $loader->dependency('jquery-scroll-up', array('jquery'));
    $loader->dependency('jquery-scroll-up-run', array('jquery', 'jquery-scroll-up'));

    $loader->localize('jquery-scroll-up', array($this, 'getScrollUpOptions'));
    $loader->load();
  }

  function initFrontEndStyles() {
    $options = array(
      'version' => Version::$version,
      'media' => 'all'
    );

    $loader = $this->lookup('stylesheetLoader');
    $loader->schedule($this->getThemeStylesheet(), $options);
    $loader->load();
  }

  function getThemeStylesheet() {
    $optionStore = $this->lookup('optionStore');
    $style = $optionStore->getOption('style');

    if ($style !== 'custom') {
      return 'jquery-scroll-up-' . $style;
    } else {
      return 'theme-custom';
    }
  }

  function getDefaultOptions() {
    return array(
      'scrollDistance' => 300,
      'scrollSpeed'    => 300,
      'animation'      => 'fade',
      'animationSpeed' => 100,
      'scrollText'     => 'Scroll To Top',
      'scrollImage'    => '',
      'style'          => 'tab'
    );
  }

  function getScrollUpOptions($script) {
    $options = $this->lookup('optionStore')->getOptions();

    if ($options['style'] == 'image') {
      $options['scrollText'] = '';
    }

    return $options;
  }

}
