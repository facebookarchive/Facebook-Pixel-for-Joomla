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

use FacebookPixel\Core\FacebookPluginConfig;
use FacebookPixel\Core\Pixel;

class FacebookJoomlaJ2Store extends FacebookJoomlaIntegrationBase {
  public static function injectPixelTrackCode() {
    $app = \JFactory::getApplication();

    $j2store_view_content_params = $app->getUserState(FacebookPluginConfig::J2STORE_VIEW_CONTENT, null);
    if ($j2store_view_content_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_VIEW_CONTENT, null);

      $script = Pixel::getPixelTrackViewContentCode($j2store_view_content_params, true);
      parent::injectScript($script);
    }

    $j2store_add_to_cart_params = $app->getUserState(FacebookPluginConfig::J2STORE_ADD_TO_CART, null);
    if ($j2store_add_to_cart_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_ADD_TO_CART, null);

      $script = Pixel::getPixelTrackAddToCartCode($j2store_add_to_cart_params, true);
      parent::injectScript($script);
    }

    $j2store_initiate_checkout_params = $app->getUserState(FacebookPluginConfig::J2STORE_INITIATE_CHECKOUT, null);
    if ($j2store_initiate_checkout_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_INITIATE_CHECKOUT, null);

      $script = Pixel::getPixelTrackInitiateCheckoutCode($j2store_initiate_checkout_params, true);
      parent::injectScript($script);
    }

    $j2store_purchase_params = $app->getUserState(FacebookPluginConfig::J2STORE_PURCHASE, null);
    if ($j2store_purchase_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_PURCHASE, null);

      $script = Pixel::getPixelTrackPurchaseCode($j2store_purchase_params, true);
      parent::injectScript($script);
    }
  }

  public static function processViewContentEvent($product) {
    $currency = \J2Store::currency()->getCode();
    $product_id = $product->j2store_product_id;
    $price = $product->pricing->price;

    $params = array(
      'content_ids' => [$product_id],
      'content_type' => 'product',
      'currency' => $currency,
      'value' => $price,
    );

    $app = \JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_VIEW_CONTENT, $params);
  }

  public static function processAddToCartEvent($cart_item, $value, $product) {
    \F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);

    $currency = \J2Store::currency()->getCode();
    $product_id = $product->j2store_product_id;
    $price = $product->pricing->price;

    $params = array(
      'content_ids' => [$product_id],
      'content_type' => 'product',
      'currency' => $currency,
      'value' => $price,
    );

    $app = \JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_ADD_TO_CART, $params);
  }

  public static function processInitiateCheckoutEvent($order) {
    $currency = \J2Store::currency()->getCode();
    $items = $order->getItems();
    $subtotal = $order->get_formatted_subtotal(false, $items);
    $content_ids = array();

    foreach ($items as $item) {
      $content_ids[] = $item->product_id;;
    }

    $params = array(
      'content_ids' => $content_ids,
      'content_type' => 'product',
      'currency' => $currency,
      'value' => $subtotal,
    );

    $app = \JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_INITIATE_CHECKOUT, $params);
  }

  public static function processPurchaseEvent($order) {
    $currency = \J2Store::currency()->getCode();
    $items = $order->getItems();
    $subtotal = $order->get_formatted_subtotal(false, $items);
    $content_ids = array();

    foreach ($items as $item) {
      $content_ids[] = $item->product_id;
    }

    $params = array(
      'content_ids' => $content_ids,
      'content_type' => 'product',
      'currency' => $currency,
      'value' => $subtotal,
    );

    $app = \JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_PURCHASE, $params);
  }
}
