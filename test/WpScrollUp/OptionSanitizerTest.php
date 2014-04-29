<?php

namespace WpScrollUp;

use Encase\Container;

class OptionSanitizerTest extends \WP_UnitTestCase {

  public $container;
  public $sanitizer;

  function setUp() {
    parent::setUp();
    $this->container = new Container();
    $this->container->singleton('optionSanitizer', 'WpScrollUp\\OptionSanitizer');
    $this->sanitizer = $this->container->lookup('optionSanitizer');
  }

  function test_it_does_not_have_errors_initially() {
    $this->assertFalse($this->sanitizer->hasErrors());
  }

  /*
* scrollDistance - 300 - number pixels
* scrollSpeed - 300 - number ms
* animation - fade - list
* animationSpeed - number ms
* scrollText - text
* scrollImg - text - false if empty or invalid
* style - tab, pill, link, image, custom
   */
}
