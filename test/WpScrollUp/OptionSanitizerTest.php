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

  function test_it_can_detect_valid_choice() {
    $choices = array('a', 'b', 'c');
    $actual = $this->sanitizer->isChoiceType('a', $choices);
    $this->assertTrue($actual);
  }

  function test_it_can_detect_invalid_choice() {
    $choices = array('a', 'b', 'c');
    $actual = $this->sanitizer->isChoiceType('d', $choices);
    $this->assertFalse($actual);
  }

  function test_it_can_detect_number_type() {
    $this->assertTrue($this->sanitizer->isNumber('500'));
  }

  function test_it_can_detect_invalid_number_type() {
    $this->assertFalse($this->sanitizer->isNumber('foo'));
  }

  function test_it_can_validate_text() {
    $actual = $this->sanitizer->validateText('<b>foo</b>');
    $this->assertEquals('foo', $actual);
  }

  function test_it_can_validate_number_type() {
    $this->assertEquals(500, $this->sanitizer->toNumber('500'));
  }

  function test_it_can_validate_invalid_number_type() {
    $this->assertFalse($this->sanitizer->toNumber('lorem'));
  }

  function test_it_can_validate_choice() {
    $choices = array('a', 'b', 'c');
    $actual = $this->sanitizer->toChoiceType('b', $choices);
    $this->assertEquals('b', $actual);
  }

  function test_it_can_validate_invalid_choice() {
    $choices = array('a', 'b', 'c');
    $actual = $this->sanitizer->toChoiceType('d', $choices);
    $this->assertFalse($actual);
  }

  function test_it_can_sanitize_valid_options() {
    $options = array(
      'style' => 'link',
      'scrollText' => '<b>foo</b>',
      'animation' => 'fade',
      'scrollDistance' => '200',
      'scrollSpeed' => 100,
    );

    $target = array();
    $actual = $this->sanitizer->sanitize($options, $target);
    $expected = array(
      'style' => 'link',
      'scrollText' => 'foo',
      'animation' => 'fade',
      'scrollDistance' => 200,
      'scrollSpeed' => 100
    );

    $this->assertEquals($expected, $actual);
  }

  function test_it_can_sanitize_invalid_options() {
    $options = array(
      'style' => 'unknown',
      'scrollText' => '<b>foo</b>',
      'animation' => 'unknown',
      'scrollDistance' => 'foo',
      'scrollSpeed' => 'bar',
    );

    $target = array();
    $actual = $this->sanitizer->sanitize($options, $target);

    $this->assertTrue($this->sanitizer->hasErrors());
    $errors = $this->sanitizer->getErrors();

    $this->assertEquals(4, count($errors));
  }

}
