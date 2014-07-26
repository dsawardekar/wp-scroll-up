<?php

namespace WpScrollUp;

class OptionsController extends \Arrow\Options\Controller {

  function patch() {
    $validator = $this->getValidator()
      ->rule('required', 'style')
      ->rule('in', 'style', $this->pluginMeta->getStyleTypes())

      ->rule('safeText', 'scrollText')->message('Unsafe Scroll Text value.')

      ->rule('required', 'scrollDistance')
      ->rule('integer', 'scrollDistance')
      ->rule('min', 'scrollDistance', 0)
      ->rule('max', 'scrollDistance', 5000)

      ->rule('required', 'scrollSpeed')
      ->rule('integer', 'scrollSpeed')
      ->rule('min', 'scrollSpeed', 0)
      ->rule('max', 'scrollSpeed', 5000)

      ->rule('required', 'animation')
      ->rule('in', 'animation', $this->pluginMeta->getAnimationTypes());

    if ($validator->validate()) {
      return parent::patch();
    } else {
      return $this->error($validator->errors());
    }
  }

}
