<?php

// No direct access.
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgSystemPixoona extends JPlugin {

  function onAfterRender() {

    $app = JFactory::getApplication();

    if ( $app->isAdmin()) return true;
    if ($app->getName() != 'site') return true;

    $pixoona_use   = $this->params->get( 'pixoona_use', 'true' );
    $pixoona_limit = $this->params->get( 'pixoona_limit', '' );
    $language = JFactory::getLanguage();
    $language = explode('-', $language->getTag());
    ( $_SERVER['SERVER_PORT'] != 80 ) ? $protocol = "https" : $protocol = "http";

    // Meta-Tag einbauen
    $meta_tag = '<meta name="pixoona" content="type=cms;system=joomla;version=1.3.5;locale='. $language[0] .';active=true;script='. $pixoona_use .'">';

    if ($pixoona_use) {
      // Inkludieren des pixoona-Javascripts
      $rpx_code = "\n<!-- pixoona -->\n";

      $rpx_code.= "<script type=\"text/javascript\" src=\"". $protocol ."://www.pixoona.com/pixtec.js\"></script>";

      // pixoona-Options
      $rpx_code.= "<script type=\"text/javascript\">\n";
      $rpx_code.= "  window.setTimeout(function() { if(typeof redpeppix != 'undefined') redpeppix.use_rpx = ". $pixoona_use ."; }, 2000);\n";

      // pixoona-Limitierung
      if ( $pixoona_limit != "" ) {
        $rpx_code.= "  var element = document.getElementById('". $pixoona_limit ."');\n";
        $rpx_code.= "  if ( element != null ) {\n";
        $rpx_code.= "    if ( element.className == '' ) {\n";
        $rpx_code.= "      element.className = 'rpx_limit';\n";
        $rpx_code.= "    } else {\n";
        $rpx_code.= "      element.className = element.className + ' rpx_limit';\n";
        $rpx_code.= "    }\n";
        $rpx_code.= "  }\n";
      }
      $rpx_code.= "</script>\n";
    }

    $html_body = JResponse::getBody();
    $html_body = str_replace ("</head>", $meta_tag. "</head>", $html_body );
    if ($pixoona_use) $html_body = str_replace ("</body>", $rpx_code. "</body>", $html_body );
    JResponse::setBody($html_body);

    return true;
  }
}

?>