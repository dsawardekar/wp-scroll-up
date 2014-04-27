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

  function test_it_has_container() {
    $this->assertInstanceOf('Encase\\Container', $this->sanitizer->container);
  }

}
