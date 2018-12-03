<?php
// Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved.

/**
 * @package     Joomla.Plugin
 * @subpackage  System.officialfacebookpixel
 *
 * @copyright   Copyright 2004-present Facebook. All Rights Reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace FacebookPixel\Tests;

use PHPUnit\Framework\TestCase;
use FacebookPixel\Core\Pixel;

define('_JEXEC', 1);

final class PixelTest extends TestCase {
  const TEST_PIXEL_ID = '12345';

  public function testCanCreatePixelInstance() {
    $pixel = new Pixel(self::TEST_PIXEL_ID);
    $this->assertInstanceOf(
      Pixel::class,
      $pixel);
  }

  public function testCanGetPixelBaseCode() {
    $pixel = new Pixel(self::TEST_PIXEL_ID);
    $base_code = $pixel->getPixelBaseCode();

    $this->assertNotEmpty($base_code);
    $this->assertTrue(strpos($base_code, '<script') !== false);
    $this->assertTrue(strpos($base_code, '</script>') !== false);
    $this->assertTrue(strpos($base_code, 'init') !== false);
    $this->assertTrue(strpos($base_code, self::TEST_PIXEL_ID) !== false);
    $this->assertTrue(strpos($base_code, Pixel::PARTNER_AGENT_NAME) !== false);
  }

  public function testCanGetPixelTrackCodeWithNonStandardEventAndScriptTag() {
    $track_code = Pixel::getPixelTrackCode('NonStandardEvent', array('key' => 'value'), true);

    $this->assertNotEmpty($track_code);
    $this->assertTrue(strpos($track_code, '<script') !== false);
    $this->assertTrue(strpos($track_code, '</script>') !== false);
    $this->assertTrue(strpos($track_code, 'trackCustom') !== false);
    $this->assertTrue(strpos($track_code, 'NonStandardEvent') !== false);
    $this->assertTrue(strpos($track_code, '"key": "value"') !== false);
  }

  public function testCanGetPixelTrackCodeWithStandardEventAndScriptTag() {
    $track_code = Pixel::getPixelTrackCode(Pixel::LEAD, array('key' => 'value'), true);

    $this->assertNotEmpty($track_code);
    $this->assertTrue(strpos($track_code, '<script') !== false);
    $this->assertTrue(strpos($track_code, '</script>') !== false);
    $this->assertTrue(strpos($track_code, 'track') !== false);
    $this->assertTrue(strpos($track_code, Pixel::LEAD) !== false);
    $this->assertTrue(strpos($track_code, '"key": "value"') !== false);
    $this->assertFalse(strpos($track_code, 'trackCustom') !== false);
  }

  public function testCanGetPixelTrackCodeWithoutScriptTag() {
    $track_code = Pixel::getPixelTrackCode(Pixel::LEAD, array('key' => 'value'), false);

    $this->assertNotEmpty($track_code);
    $this->assertTrue(strpos($track_code, 'track') !== false);
    $this->assertTrue(strpos($track_code, Pixel::LEAD) !== false);
    $this->assertTrue(strpos($track_code, '"key": "value"') !== false);
    $this->assertFalse(strpos($track_code, 'trackCustom') !== false);
    $this->assertFalse(strpos($track_code, '<script') !== false);
    $this->assertFalse(strpos($track_code, '</script>') !== false);
  }
}
