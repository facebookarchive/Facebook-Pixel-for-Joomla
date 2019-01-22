<?php
// Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved.

/**
 * @package     Joomla.Plugin
 * @subpackage  System.officialfacebookpixel
 *
 * @copyright   Copyright 2004-present Facebook. All Rights Reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace FacebookPixel\Core;

// No direct access
defined('_JEXEC') or die;

/**
 * Facebook Plugin Config
 */
class FacebookPluginConfig {
  const PLUGIN_VERSION = '1.0.3';

  const PARTNER_NAME = 'Joomla';
  const PIXEL_AGENT_NAME = 'pljoomla';

  const INTEGRATIONS_NAMESPACE_PREFIX = 'FacebookPixel\\Integrations\\';

  // The config for integrations: INTEGRATION_KEY => PLUGIN_CLASS_NAME
  public static function getIntegrationConfig() {
    return array(
      'JOOMLA_CONTACT_FORM' => 'FacebookJoomlaContactForm',
      'J2STORE_SHOPPING_CART' => 'FacebookJoomlaJ2Store',
    );
  }
}
