<?php

namespace WpScrollUp;

class OptionStore {

  public $pluginSlug;
  public $defaults = array(
    'foo' => 'bar'
  );
  protected $didLoad = false;
  protected $options = null;

  function needs() {
    return array('pluginSlug', 'optionSanitizer');
  }

  function getGroupName() {
    return $this->pluginSlug . "-option-store";
  }

  function getSettingName() {
    return $this->pluginSlug . "-options";
  }

  function toJSON($options) {
    return json_encode($options);
  }

  function toOptions($json) {
    return json_decode($json, true);
  }

  function loaded() {
    return $this->didLoad;
  }

  function load() {
    if ($this->didLoad) {
      return $this->options;
    }

    $options = get_option($this->getSettingName());
    if ($options === false) {
      $options = $this->defaults;
    } else {
      $options = $this->toOptions($options);
    }

    $this->options = $options;
    $this->didLoad = true;

    return $this->options;
  }

  function clear() {
    delete_option($this->getSettingName());
    $this->didLoad = false;
    $this->options = null;
  }

  function getOptions() {
    $this->load();
    return $this->options;
  }

  function getOption($name) {
    $this->load();
    if (array_key_exists($name, $this->options)) {
      return $this->options[$name];
    } else {
      return $this->defaults[$name];
    }
  }

  function register() {
    $callback = array($this, 'sanitize');
    register_setting($this->getGroupName(), $this->getSettingName(), $callback);
  }

  function sanitize($options) {
    $sanitized = $this->optionSanitizer->sanitize($options);
    $json = $this->toJSON($sanitized);
    $saveable = array();
    $saveable[$this->getSettingName()] = $json;

    return $saveable;
  }
}
