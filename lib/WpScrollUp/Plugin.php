<?php

namespace WpScrollUp;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta', new PluginMeta($file))
      ->packager('optionsPackager', 'Arrow\Options\Packager');
  }

  function enable() {
    add_action('init', array($this, 'initFrontEnd'));
  }

  function initFrontEnd() {
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
