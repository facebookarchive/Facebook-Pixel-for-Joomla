<?php
// Copyright (c) Facebook, Inc. and its affiliates. All Rights Reserved.

/**
 * @package     Joomla.Plugin
 * @subpackage  System.officialfacebookpixel
 *
 * @copyright   Copyright 2004-present Facebook. All Rights Reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use FacebookPixel\Core\FacebookPluginConfig;
use FacebookPixel\Core\Pixel;

/**
 * Facebook Pixel Plugin
 */
class PlgSystemOfficialFacebookPixel extends JPlugin {
  /**
   * The event triggered after the router has routed the client request
   */
  public function onAfterRoute() {
    require_once(__DIR__ . '/vendor/autoload.php');
  }

  /**
   * The event trigged before Head section of the Document is created
   *
   * @return void
   */
  public function onBeforeCompileHead() {
    $app = JFactory::getApplication();

    if ($app->isClient('administrator')) {
      return true;
    }

    if ($this->params->get('pixel_id', false)) {
      $pixel_id = $this->params->get('pixel_id');
      $this->injectPixelBaseCode($pixel_id);
    }

    $is_form_submitted = $app->getUserState(FacebookPluginConfig::SUBMIT_JOOMLA_CONTACT_FORM, false);
    if ($is_form_submitted) {
      // Reset the user state
      $app->setUserState(FacebookPluginConfig::SUBMIT_JOOMLA_CONTACT_FORM, false);

      $script = Pixel::getPixelTrackLeadCode(array(), true);
      $this->injectPixelTrackCode($script);
    }

    $j2store_view_content_params = $app->getUserState(FacebookPluginConfig::J2STORE_VIEW_CONTENT, null);
    if ($j2store_view_content_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_VIEW_CONTENT, null);

      $script = Pixel::getPixelTrackViewContentCode($j2store_view_content_params, true);
      $this->injectPixelTrackCode($script);
    }

    $j2store_add_to_cart_params = $app->getUserState(FacebookPluginConfig::J2STORE_ADD_TO_CART, null);
    if ($j2store_add_to_cart_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_ADD_TO_CART, null);

      $script = Pixel::getPixelTrackAddToCartCode($j2store_add_to_cart_params, true);
      $this->injectPixelTrackCode($script);
    }

    $j2store_initiate_checkout_params = $app->getUserState(FacebookPluginConfig::J2STORE_INITIATE_CHECKOUT, null);
    if ($j2store_initiate_checkout_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_INITIATE_CHECKOUT, null);

      $script = Pixel::getPixelTrackInitiateCheckoutCode($j2store_initiate_checkout_params, true);
      $this->injectPixelTrackCode($script);
    }

    $j2store_purchase_params = $app->getUserState(FacebookPluginConfig::J2STORE_PURCHASE, null);
    if ($j2store_purchase_params !== null) {
      $app->setUserState(FacebookPluginConfig::J2STORE_PURCHASE, null);

      $script = Pixel::getPixelTrackPurchaseCode($j2store_purchase_params, true);
      $this->injectPixelTrackCode($script);
    }
  }

  /**
   * The event triggered after a contact form is submitted
   *
   * @return void
   */
  public function onSubmitContact($contact, $data) {
    $app = JFactory::getApplication();

    $app->setUserState(FacebookPluginConfig::SUBMIT_JOOMLA_CONTACT_FORM, true);
  }

  /**
   * The event triggered after product detail page is opened for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreViewProduct($product) {
    $currency = J2Store::currency()->getCode();
    $product_id = $product->j2store_product_id;
    $price = $product->pricing->price;

    $params = array(
      'content_ids' => [$product_id],
      'content_type' => 'product',
      'currency' => $currency,
      'value' => $price,
    );

    $app = JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_VIEW_CONTENT, $params);
  }

  /**
   * The event triggered after add to cart button is clicked for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreBeforeAddToCart($cart_item, $value, $product) {
    F0FModel::getTmpInstance('Products', 'J2StoreModel')->runMyBehaviorFlag(true)->getProduct($product);

    $currency = J2Store::currency()->getCode();
    $product_id = $product->j2store_product_id;
    $price = $product->pricing->price;

    $params = array(
      'content_ids' => [$product_id],
      'content_type' => 'product',
      'currency' => $currency,
      'value' => $price,
    );

    $app = JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_ADD_TO_CART, $params);
  }

  /**
   * The event triggered after order is placed for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreAfterPayment($order) {
    $currency = J2Store::currency()->getCode();
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

    $app = JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_PURCHASE, $params);
  }

  /**
   * The event triggered after checkout is clicked for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreBeforeCheckout($order) {
    $currency = J2Store::currency()->getCode();
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

    $app = JFactory::getApplication();
    $app->setUserState(FacebookPluginConfig::J2STORE_INITIATE_CHECKOUT, $params);
  }

  /**
   * Inject pixel code into HTML head
   *
   * @param string $pixel_id Pixel id that pixel code is setup for.
   *
   * @return void
   */
  private function injectPixelBaseCode($pixel_id) {
    $pixel = new Pixel($pixel_id);
    $pixel_code = $pixel->getPixelBaseCode();

    $document = JFactory::getDocument();
    $document->addCustomTag($pixel_code);
  }

  /**
   * Inject pixel code into HTML body
   */
  private function injectPixelTrackCode($script) {
    $app = JFactory::getApplication();

    $document = JFactory::getDocument();
    $document->addCustomTag($script);
  }
}
