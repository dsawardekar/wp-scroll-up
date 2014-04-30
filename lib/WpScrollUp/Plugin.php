<?php

namespace WpScrollUp;

use Encase\Container;
use WordPress\TwigHelper;

class Plugin {

  static $version = '0.1.0';
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
  public $initedOptionStore;

  function __construct($file) {
    $container = new Container();
    $container->object('pluginFile', $file);
    $container->object('pluginDir', $this->toPluginDir($file));
    $container->object('pluginSlug', 'wp_scroll_up');

    $container->factory('script', 'WpScrollUp\\Script');
    $container->factory('stylesheet', 'WpScrollUp\\Stylesheet');
    $container->singleton('scriptLoader', 'WpScrollUp\\ScriptLoader');
    $container->singleton('stylesheetLoader', 'WpScrollUp\\StylesheetLoader');

    $container->singleton('twigHelper', 'WordPress\\TwigHelper');

    $container->object('optionName', 'wp_scroll_up_options');
    $container->object('defaultOptions', $this->getDefaultOptions());
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

  function configureOptionStore() {
    if ($this->initedOptionStore === true) {
      return;
    }

    $optionStore = $this->lookup('optionStore');
    $optionStore->setDefaults($this->getDefaultOptions());
    $optionStore->setPluginSlug($this->lookup('pluginSlug'));
    $optionStore->setOptionName($this->lookup('pluginSlug') .  '_options');
    $optionStore->setSanitizer($this->lookup('optionSanitizer'));

    $this->initedOptionStore = true;
  }

  function initOptionStore() {
    $this->configureOptionStore();

    $optionStore = $this->lookup('optionStore');
    $optionStore->register();
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
      'version' => Plugin::$version,
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
      'version' => Plugin::$version,
      'media' => 'all'
    );

    $loader = $this->lookup('stylesheetLoader');
    $loader->schedule($this->getThemeStylesheet(), $options);
    $loader->load();
  }

  function getThemeStylesheet() {
    $this->configureOptionStore();
    $optionStore = $this->lookup('optionStore');

    return 'jquery-scroll-up-' .  $optionStore->getOption('style');
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
    $this->configureOptionStore();
    $optionStore = $this->lookup('optionStore');
    return $optionStore->getOptions();
  }

}
