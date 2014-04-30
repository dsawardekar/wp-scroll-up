<?php

namespace WpScrollUp;

class OptionSanitizer {

  protected $errors = array();
  protected $options;

  public function sanitize(&$options, &$target) {
    $this->errors = array();
    $this->options = $options;

    foreach ($options as $key => $value) {
      $method = 'validate' . ucfirst($key);

      if (method_exists($this, $method)) {
        $result = call_user_func(array($this, $method), $value);
        if ($result === false) {
          $this->addError($key, $value);
        } else {
          $target[$key] = $value;
        }
      } else {
        /* without validator assumed to be invalid */
        $this->addError($key, $value);
      }
    }

    return $target;
  }

  public function hasErrors() {
    return count($this->errors) > 0;
  }

  public function getErrors() {
    return $this->errors;
  }

  function validateScrollDistance($value) {
    return $this->validateNumber($value);
  }

  function validateScrollSpeed($value) {
    return $this->validateNumber($value);
  }

  function validateAnimation($value) {
    return $this->validateChoice($value, $this->getAnimationTypes());
  }

  function validateAnimationSpeed($value) {
    return $this->validateNumber($value);
  }

  function validateScrollText($value) {
    return $this->validateText($value);
  }

  function validateScrollImage($value) {
    return $value;
  }

  function validateStyle($value) {
    return $this->validateChoice($value, $this->getStyles());
  }

  function validateText($value) {
    if ($value !== '') {
      return $value;
    } else {
      return false;
    }
  }

  function validateNumber($value) {
    if ($this->isNumber($value)) {
      return $this->toNumber($value);
    } else {
      return false;
    }
  }

  function validateChoice($value, $choices) {
    if ($this->isChoiceType($value, $choices)) {
      return $value;
    } else {
      return false;
    }
  }

  function addError($field, $value, $message = null) {
    $error = new SanitizationError($field, $value, $message);
    array_push($this->errors, $error);
  }

  function toNumber($value, $default = 100) {
    if ($this->isNumber($value)) {
      return intval($value);
    } else {
      return $default;
    }
  }

  function isNumber($value) {
    return intval($value) > 0;
  }

  function toChoiceType($value, $defaults) {
    if ($this->isChoiceType($value, $defaults)) {
      return $value;
    } else {
      return 'none';
    }
  }

  function isChoiceType($value, $defaults) {
    return in_array($value, $defaults);
  }

  function getAnimationTypes() {
    return array('fade', 'none');
  }

  function getStyles() {
    return array('tab', 'pill', 'link', 'image', 'custom');
  }

}

class SanitizationError {

  public $field;
  public $value;
  public $message;

  function __construct($field, $value, $message = null) {
    $this->field   = $field;
    $this->value   = $value;

    if (is_null($message)) {
      $message = "Invalid input for $field: \"$value\"";
    }

    $this->message = $message;
  }
}


