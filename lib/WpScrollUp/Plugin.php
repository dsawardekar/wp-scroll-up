<?php

namespace WpScrollUp;

class Plugin extends \Arrow\Plugin {

  function __construct($file) {
    parent::__construct($file);

    $this->container
      ->object('pluginMeta'           , new PluginMeta($file))
      ->packager('optionsPackager'    , 'Arrow\Options\Packager')
      ->singleton('optionsController' , 'WpScrollUp\OptionsController')
      ->singleton('frontEndManifest'  , 'WpScrollUp\FrontEndManifest');
  }

  function enable() {
    add_action('init', array($this, 'onInit'));
  }

  function onInit() {
    $this->lookup('frontEndManifest')->load(false);
  }

}
