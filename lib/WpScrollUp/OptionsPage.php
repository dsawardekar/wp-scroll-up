<?php

namespace WpScrollUp;

class OptionsPage extends \Arrow\OptionsManager\OptionsPage {

  function getTemplateContext() {
    $context = array(
      'animationTypes' => $this->pluginMeta->getAnimationTypes(),
      'styleTypes' => $this->pluginMeta->getStyleTypes()
    );

    $options = $this->pluginMeta->getDefaultOptions();
    foreach ($options as $key => $value) {
      $context[$key] = $this->getOption($key);
    }

    return $context;
  }

}
