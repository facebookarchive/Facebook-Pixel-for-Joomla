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

use FacebookPixel\Core\Pixel;

class FacebookJoomlaContactForm extends FacebookJoomlaIntegrationBase {
  const SUBMIT_JOOMLA_CONTACT_FORM = 'facebook.joomla.contact_form_submitted';

  public static function injectPixelTrackCode() {
    $app = \JFactory::getApplication();

    $is_form_submitted = $app->getUserState(self::SUBMIT_JOOMLA_CONTACT_FORM, false);
    if ($is_form_submitted) {
      // Reset the user state
      $app->setUserState(self::SUBMIT_JOOMLA_CONTACT_FORM, false);

      $script = Pixel::getPixelTrackLeadCode(array(), true);
      parent::injectScript($script);
    }
  }

  public static function processLeadEvent() {
    $app = \JFactory::getApplication();
    $app->setUserState(self::SUBMIT_JOOMLA_CONTACT_FORM, true);
  }
}
