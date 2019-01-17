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
use FacebookPixel\Integrations\FacebookJoomlaContactForm;
use FacebookPixel\Integrations\FacebookJoomlaJ2Store;

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

    FacebookJoomlaContactForm::injectPixelTrackCode();
    FacebookJoomlaJ2Store::injectPixelTrackCode();
  }

  /**
   * The event triggered after a contact form is submitted
   *
   * @return void
   */
  public function onSubmitContact($contact, $data) {
    FacebookJoomlaContactForm::processLeadEvent();
  }

  /**
   * The event triggered after product detail page is opened for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreViewProduct($product) {
    FacebookJoomlaJ2Store::processViewContentEvent($product);
  }

  /**
   * The event triggered after add to cart button is clicked for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreBeforeAddToCart($cart_item, $value, $product) {
    FacebookJoomlaJ2Store::processAddToCartEvent($cart_item, $value, $product);
  }

  /**
   * The event triggered after order is placed for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreAfterPayment($order) {
    FacebookJoomlaJ2Store::processPurchaseEvent($order);
  }

  /**
   * The event triggered after checkout is clicked for J2Store plugin
   *
   * @return void
   */
  public function onJ2StoreBeforeCheckout($order) {
    FacebookJoomlaJ2Store::processInitiateCheckoutEvent($order);
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
}
