<?php

namespace WpScrollUp;

class OptionPage {

  function needs() {
    return array('twigHelper', 'optionStore');
  }

  function getPageTitle() {
    return 'wp-scroll-up | Settings';
  }

  function getMenuTitle() {
    return 'wp-scroll-up Settings';
  }

  function getCapability() {
    return 'manage_options';
  }

  function getMenuSlug() {
    return 'wp-scroll-up-settings';
  }

  function register() {
    add_options_page(
      $this->getPageTitle(),
      $this->getMenuTitle(),
      $this->getCapability(),
      $this->getMenuSlug(),
      array($this, 'show')
    );
  }

  function show() {
    $context = $this->getTemplateContext();
    $this->twigHelper->render('options_form', $context);
  }

  function getTemplateContext() {
    $context = array(

    );

    return $context;
  }

}
