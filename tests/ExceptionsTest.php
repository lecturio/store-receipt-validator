<?php

/**
 * @group library
 */
class ExceptionsTest extends \PHPUnit\Framework\TestCase
{

  public function testRunTimeException()
  {
    $e = new \ReceiptValidator\RunTimeException();

    $this->assertInstanceOf("\ReceiptValidator\RunTimeException", $e);
  }
}
