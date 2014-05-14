<?php

namespace WpScrollUp;

class OptionsValidator extends \Arrow\OptionsManager\OptionsValidator {

  function needs() {
    return array('pluginMeta');
  }

  function loadRules($validator) {
    $validator->rule('required', 'style');
    $validator->rule('in', 'style', $this->pluginMeta->getStyleTypes());

    $validator->rule('required', 'scrollDistance');
    $validator->rule('integer', 'scrollDistance');
    $validator->rule('min', 'scrollDistance', 0);
    $validator->rule('max', 'scrollDistance', 5000);

    $validator->rule('required', 'scrollSpeed');
    $validator->rule('integer', 'scrollSpeed');
    $validator->rule('min', 'scrollSpeed', 0);
    $validator->rule('max', 'scrollSpeed', 5000);

    $validator->rule('required', 'animation');
    $validator->rule('in', 'animation', $this->pluginMeta->getAnimationTypes());
  }

}
