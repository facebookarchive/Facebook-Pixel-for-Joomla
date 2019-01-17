<?php
// Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved.

/**
 * @package     Joomla.Plugin
 * @subpackage  System.officialfacebookpixel
 *
 * @copyright   Copyright 2004-present Facebook. All Rights Reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace FacebookPixel\Integrations;

// No direct access
defined('_JEXEC') or die;

abstract class FacebookJoomlaIntegrationBase {
  /**
   * Inject the pixel track code if a specific event is triggered
   */
  abstract public static function injectPixelTrackCode();

  /**
   * Inject pixel code into HTML body
   */
  final protected static function injectScript($script) {
    $document = \JFactory::getDocument();
    $document->addCustomTag($script);
  }
}
