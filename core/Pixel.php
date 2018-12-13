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

use ReflectionClass;

/**
 * Facebook pixel object
 */
class Pixel {
  const ADDPAYMENTINFO = 'AddPaymentInfo';
  const ADDTOCART = 'AddToCart';
  const ADDTOWISHLIST = 'AddToWishlist';
  const COMPLETEREGISTRATION = 'CompleteRegistration';
  const CONTACT = 'Contact';
  const CUSTOMIZEPRODUCT = 'CustomizeProduct';
  const DONATE = 'Donate';
  const FINDLOCATION = 'FindLocation';
  const INITIATECHECKOUT = 'InitiateCheckout';
  const LEAD = 'Lead';
  const PAGEVIEW = 'PageView';
  const PURCHASE = 'Purchase';
  const SCHEDULE = 'Schedule';
  const SEARCH = 'Search';
  const STARTTRIAL = 'StartTrial';
  const SUBMITAPPLICATION = 'SubmitApplication';
  const SUBSCRIBE = 'Subscribe';
  const VIEWCONTENT = 'ViewContent';

  const PARTNER_NAME = 'Joomla';
  const PARTNER_AGENT_NAME = 'pljoomla';

  const SCRIPT_TAG_HEAD = "<script type='text/javascript'>";
  const SCRIPT_TAG_TAIL = '</script>';

  const PIXEL_CODE = "
<!-- %s Facebook Integration Begin -->
<script type='text/javascript'>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '%s', {}, %s);
fbq('track', 'PageView');
</script>
<noscript>
<img height=\"1\" width=\"1\" style=\"display:none\" alt=\"fbpx\"
src=\"https://www.facebook.com/tr?id=%s&ev=PageView&noscript=1\"/>
</noscript>
<!-- DO NOT MODIFY -->
<!-- %s Facebook Integration end -->
";

  private static $pixelID;

  private static $pixelFbqTrackCode = "
fbq('%s', '%s', %s);
";

  public function __construct($pixel_id = '0') {
    self::$pixelID = $pixel_id;
  }

  /**
   * Returns Facebook pixel base code script
   */
  public function getPixelBaseCode() {
    return sprintf(self::PIXEL_CODE,
      self::PARTNER_NAME,
      self::$pixelID,
      self::getParameters(),
      self::$pixelID,
      self::PARTNER_NAME);
  }

  /**
   * Returns Facebook pixel conversion track code
   */
  public static function getPixelTrackCode($event, $param = array(), $with_script_tag = true) {
    $track_code = $with_script_tag ?
      self::SCRIPT_TAG_HEAD.self::$pixelFbqTrackCode.self::SCRIPT_TAG_TAIL :
      self::$pixelFbqTrackCode;

    $param_str = is_array($param) ? json_encode($param, JSON_PRETTY_PRINT) : $param;

    $class = new ReflectionClass(__CLASS__);

    return sprintf(
      $track_code,
      $class->getConstant(strtoupper($event)) !== false ? 'track' : 'trackCustom',
      $event,
      $param_str);
  }

  /**
   * Gets Facebook pixel track code for AddToCart event
   */
  public static function getPixelTrackAddToCartCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::ADDTOCART,
      $param,
      $with_script_tag);
  }

  /**
   * Gets Facebook pixel track code for Lead event
   */
  public static function getPixelTrackLeadCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::LEAD,
      $param,
      $with_script_tag);
  }

  /**
   * Gets Facebook pixel track code for InitiateCheckout event
   */
  public static function getPixelTrackInitiateCheckoutCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::INITIATECHECKOUT,
      $param,
      $with_script_tag);
  }

  /**
   * Gets Facebook pixel track code for Purchase event
   */
  public static function getPixelTrackPurchaseCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::PURCHASE,
      $param,
      $with_script_tag);
  }

  /**
   * Gets Facebook pixel track code for ViewContent event
   */
  public static function getPixelTrackViewContentCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::VIEWCONTENT,
      $param,
      $with_script_tag);
  }

  /**
   * Returns Facebook pixel code script parameters part
   */
  private function getParameters() {
    return "{agent: '".self::PARTNER_AGENT_NAME."'}";
  }
}
