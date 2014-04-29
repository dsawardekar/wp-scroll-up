<?php

namespace WpScrollUp;

class OptionPage {

  function needs() {
    return array('twigHelper', 'optionStore', 'pluginSlug');
  }

  function getPageTitle() {
    return 'wp-scroll-up | Settings';
  }

  function getMenuTitle() {
    return 'WP Scroll Up';
  }

  function getCapability() {
    return 'manage_options';
  }

  function getMenuSlug() {
    return $this->pluginSlug;
  }

  function register() {
    add_options_page(
      $this->getPageTitle(),
      $this->getMenuTitle(),
      $this->getCapability(),
      $this->getMenuSlug(),
      array($this, 'show')
    );
  }

  function show() {
    $context = $this->getTemplateContext();
    $this->twigHelper->display('options_form', $context);
  }

  function getTemplateContext() {
    $context = array(
      'settings_fields' => $this->getSettingsFields($this->pluginSlug),
      'animationTypes' => array('fade', 'none'),
      'styleTypes' => array('tab', 'pill', 'link', 'image')
    );

    $options = $this->optionStore->getOptions();
    foreach ($options as $key => $value) {
      $context[$key] = $value;
    }

    return $context;
  }

  function getSettingsFields($slug) {
    ob_start();
    settings_fields($slug);
    return ob_get_clean();
  }

  function dump($key, $value) {
    ob_start();
    var_dump($value);
    $dumped = ob_get_clean();

    error_log("$key: $dumped");
  }

}
