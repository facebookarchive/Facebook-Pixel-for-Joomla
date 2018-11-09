<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.facebookpixel
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
class PlgSystemFacebookPixel extends JPlugin {
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
