<?php
/**
 * @version   $Id: pixoona.php 1337 2011-12-13 11:52:26Z cworreschk $
 * @copyright Copyright (C) 2011-2012 redpeppix. GmbH & Co. KG. All rights reserved. (www.redpeppix.com)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemPixoona extends JPlugin {

  function onAfterRender() {

    $app = JFactory::getApplication();
    if ( $app->isAdmin()) return;

    $rpx_use   = $this->params->get( 'rpx_use', 'true' );
    $rpx_limit = $this->params->get( 'rpx_limit', '' );
    ( $_SERVER['SERVER_PORT'] != 80 ) ? $protocol = "https" : $protocol = "http";

    // Inkludieren des pixoona-Javascripts
    $rpx_code = "\n<!-- pixoona -->\n";
    $rpx_code.= "<script type=\"text/javascript\" src=\"". $protocol ."://www.pixoona.com/pixtec.js\"></script>";

    // pixoona-Options
    $rpx_code.= "<script type=\"text/javascript\">\n";
    $rpx_code.= "  if ( typeof(redpeppix) != 'undefined' ) {\n";
    $rpx_code.= "    redpeppix.use_rpx = ". $rpx_use .";\n";
    $rpx_code.= "  }\n";

    // pixoona-Limitierung
    if ( $rpx_limit != "" ) {
      $rpx_code.= "  var element = document.getElementById('". $rpx_limit ."');\n";
      $rpx_code.= "  if ( element != null ) {\n";
      $rpx_code.= "    if ( element.className == '' ) {\n";
      $rpx_code.= "      element.className = 'rpx_limit';\n";
      $rpx_code.= "    } else {\n";
      $rpx_code.= "      element.className = element.className + ' rpx_limit';\n";
      $rpx_code.= "    }\n";
      $rpx_code.= "  }\n";
    }
    $rpx_code.= "</script>\n";

    $html_body = JResponse::getBody();
    $html_body = str_replace ("</body>", $rpx_code. "</body>", $html_body );
    JResponse::setBody($html_body);

    return true;
  }

}

?>