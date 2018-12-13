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
  const J2STORE_ADD_TO_CART = 'facebook.j2store.add_to_cart';
  const J2STORE_INITIATE_CHECKOUT = 'facebook.j2store.initiate_checkout';
  const J2STORE_PURCHASE = 'facebook.j2store.purchase';
  const J2STORE_VIEW_CONTENT = 'facebook.j2store.view_content';
  const SUBMIT_JOOMLA_CONTACT_FORM = 'facebook.joomla.contact_form_submitted';
}
