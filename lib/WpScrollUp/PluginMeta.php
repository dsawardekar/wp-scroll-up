<?php

namespace WpScrollUp;

class PluginMeta extends \Arrow\PluginMeta {

  function getVersion() {
    return Version::$version;
  }

  function getDefaultOptions() {
    return array(
      'style'          => 'image',
      'scrollText'     => 'Scroll To Top',
      'scrollDistance' => 300,
      'scrollSpeed'    => 300,
      'animation'      => 'fade',
    );
  }

  function getStyleTypes() {
    return array('tab', 'pill', 'link', 'image', 'custom');
  }

  function getAnimationTypes() {
    return array('fade', 'none');
  }

  function getOptionsContext() {
    $optionsStore = $this->lookup('optionsStore');
    return $optionsStore->getOptions();
  }

  function getLocalizedStrings() {
    $strings = array(
      'styleTypes'     => $this->getStyleTypes(),
      'animationTypes' => $this->getAnimationTypes()
    );

    return $strings;
  }

}
