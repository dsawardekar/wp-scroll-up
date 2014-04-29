<?php

namespace WpScrollUp;

use WpScrollUp\AssetLoader;

class StylesheetLoader extends AssetLoader {

  public function assetType() {
    return 'stylesheet';
  }

  function enqueueAction() {
    return 'wp_enqueue_styles';
  }

}
