<?php

namespace WpScrollUp;

class OptionStore {

  protected $pluginSlug;
  protected $optionName;
  protected $sanitizer;
  protected $defaults = array();
  protected $didLoad = false;
  protected $options = null;
  protected $didSanitize = false;

  public function setPluginSlug($pluginSlug) {
    $this->pluginSlug = $pluginSlug;
  }

  public function getPluginSlug() {
    return $this->pluginSlug;
  }

  public function setOptionName($optionName) {
    $this->optionName = $optionName;
  }

  public function getOptionName() {
    return $this->optionName;
  }

  public function setDefaults($defaults) {
    $this->defaults = $defaults;
  }

  public function getDefaults() {
    return $this->defaults;
  }

  public function setSanitizer($sanitizer) {
    $this->sanitizer = $sanitizer;
  }

  public function getSanitizer() {
    return $this->sanitizer;
  }

  public function loaded() {
    return $this->didLoad;
  }

  public function load() {
    if ($this->didLoad) {
      return $this->options;
    }

    $json          = get_option($this->getOptionName());
    $this->didLoad = true;
    $this->options = $this->parse($json);

    return $this->options;
  }

  public function reload() {
    $this->didLoad = false;
    $this->load();
  }

  public function save() {
    $json       = $this->toJSON($this->options);
    update_option($this->getOptionName(), $json);
  }

  public function clear() {
    delete_option($this->getOptionName());

    $this->didLoad = false;
    $this->options = null;
  }

  public function getOptions() {
    $this->load();
    return $this->options;
  }

  public function getOption($name) {
    $this->load();

    if (array_key_exists($name, $this->options)) {
      $value = $this->options[$name];
    } else {
      $value = $this->defaults[$name];
    }

    return $value;
  }

  public function setOption($name, $value) {
    $this->options[$name] = $value;
  }

  public function register() {
    register_setting(
      $this->getPluginSlug(),
      $this->getOptionName(),
      array($this, 'sanitize')
    );
  }

  public function sanitize($options) {
    /* prevents double sanitization */
    if ($this->isSanitized($options)) {
      return $options;
    }

    $target    = $this->getOptions();
    $sanitized = $this->sanitizer->sanitize($options, $target);

    if (!$this->sanitizer->hasErrors()) {
      $json = $this->toJSON($sanitized);
    } else {
      $json = $this->toJSON($target);
      $this->notifyErrors($this->sanitizer->getErrors());
    }

    $this->didSanitize = true;

    return $json;
  }

  /* Helpers */
  function isSanitized($options) {
    return is_string($options) && $this->didSanitize;
  }

  function notifyErrors($errors) {
    foreach ($errors as $error) {
      add_settings_error(
        $this->getPluginSlug(), null, $error->message, 'error'
      );
    }
  }

  function parse($json) {
    if ($json !== false) {
      $options = $this->toOptions($json);
    } else {
      $options = $this->defaults;
    }

    if (is_null($options)) {
      $options = $this->defaults;
    }

    return $options;
  }

  function toJSON(&$options) {
    return json_encode($options);
  }

  function toOptions($json) {
    return json_decode($json, true);
  }

}
