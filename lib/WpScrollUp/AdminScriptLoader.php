<?php

namespace WpScrollUp;

class AdminScriptLoader extends ScriptLoader {

  function needs() {
    return array('pluginSlug');
  }

  function enqueueAction() {
    return 'admin_enqueue_scripts';
  }

  function start() {
    add_action($this->startAction(), array($this, 'load'));
  }

  function startAction() {
    return 'load-settings_page_' . $this->pluginSlug;
  }

}
