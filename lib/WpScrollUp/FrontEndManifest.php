<?php

namespace WpScrollUp;

class FrontEndManifest extends \Arrow\Asset\Manifest {

  public $pluginMeta;
  public $optionsStore;

  function __construct() {
    $this->setContext(array($this, 'getFrontEndContext'));
  }

  function needs() {
    return array_merge(
      parent::needs(),
      array('pluginMeta', 'optionsStore')
    );
  }

  function getScripts() {
    return array(
      'jquery',
      'jquery-scroll-up',
      'wp-scroll-up-options'
    );
  }

  function getStyles() {
    $style = $this->optionsStore->getOption('style');

    if ($style === 'custom') {
      if ($this->pluginMeta->hasCustomStylesheet()) {
        return array('theme-custom');
      } else {
        return array('wp-scroll-up-image');
      }
    } else {
      return array('wp-scroll-up-' . $style);
    }
  }

  function getFrontEndContext($script) {
    $strings = array();
    $options = $this->optionsStore->getOptions();

    // if the custom stylesheet is not found, we override to 'image'
    // which does not have scrollText
    if ($options['style'] === 'image' || $options['style'] === 'custom' && !$this->pluginMeta->hasCustomStylesheet()) {
      $options['scrollText'] = '';
    }

    $strings['options'] = $options;

    return $strings;
  }

  function getLocalizerVariable() {
    $slug = $this->pluginMeta->getSlug();
    return str_replace('-', '_', $slug);
  }

}
