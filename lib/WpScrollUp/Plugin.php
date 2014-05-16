<?php

namespace WpScrollUp;

use Arrow\AssetManager\AssetManager;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->object('assetManager', new AssetManager($this->container))
      ->object('optionsManager', new OptionsManager($this->container));
  }

  function enable() {
    add_action('admin_init', array($this, 'initAdmin'));
    add_action('admin_menu', array($this, 'initAdminMenu'));
    add_action('init', array($this, 'initPlugin'));
  }

  function toPluginDir($file) {
    return untrailingslashit(plugin_dir_path($file));
  }

  function initAdmin() {
    $this->lookup('optionsPostHandler')->enable();
  }

  function initAdminMenu() {
    $this->lookup('optionsPage')->register();
  }

  function initPlugin() {
    $this->initFrontEndScripts();
    $this->initFrontEndStyles();
  }

  function initFrontEndScripts() {
    $loader = $this->lookup('scriptLoader');
    $loader->schedule(
      'jquery-scroll-up', array('dependencies' => array('jquery'))
    );

    $loader->schedule(
      'jquery-scroll-up-options', array(
        'dependencies' => 'jquery-scroll-up',
        'localizer' => array($this, 'getScrollUpOptions')
      )
    );

    $loader->load();
  }

  function initFrontEndStyles() {
    $loader = $this->lookup('stylesheetLoader');
    $loader->schedule($this->getThemeStylesheet());
    $loader->load();
  }

  function getThemeStylesheet() {
    $optionStore = $this->lookup('optionsStore');
    $pluginMeta  = $this->lookup('pluginMeta');
    $style       = $optionStore->getOption('style');

    if ($style === 'custom' && $pluginMeta->hasCustomStylesheet()) {
      return 'theme-custom';
    } else {
      return 'jquery-scroll-up-' . $style;
    }
  }

  function getScrollUpOptions($script) {
    $options = $this->lookup('optionsStore')->getOptions();

    if ($options['style'] == 'image') {
      $options['scrollText'] = '';
    }

    return $options;
  }

}
