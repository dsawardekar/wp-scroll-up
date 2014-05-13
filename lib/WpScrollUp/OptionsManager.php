<?php

namespace WpScrollUp;

class OptionsManager extends \Arrow\OptionsManager\OptionsManager {

  function __construct($container) {
    parent::__construct($container);

    $container
      ->singleton('optionsPage', 'WpScrollUp\OptionsPage')
      ->singleton('optionsValidator', 'WpScrollUp\OptionsValidator');
  }

}
