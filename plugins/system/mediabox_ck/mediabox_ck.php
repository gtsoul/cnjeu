<?php

/**
 * @copyright	Copyright (C) 2011 Cédric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');


class plgSystemMediabox_ck extends JPlugin {

    function plgSystemMediabox_ck(&$subject, $config) {
        parent :: __construct($subject, $config);
        
        
    }

    function onAfterDispatch() {
        
		
		/* Fonction pour gérer le chargement du plugin */

        // recupere l'ID de la page
        $id = JRequest::getInt('Itemid');

        // charge les parametres
        $IDs = explode(",", $this->params->get('pageselect', '0'));
		$cornerradius = $this->params->get('cornerradius', '10');
		$shadowoffset = $this->params->get('shadowoffset', '5');
		$overlayopacity = $this->params->get('overlayopacity', '0.7');
		$bgcolor = $this->params->get('bgcolor', '#1a1a1a');
		$overlaycolor = $this->params->get('overlaycolor', '#000');
		$text1color = $this->params->get('text1color', '#999');
		$text2color = $this->params->get('text2color', '#fff');
		$resizeopening = $this->params->get('resizeopening', 'true');
		$resizeduration = $this->params->get('resizeduration', '240');
		$resizetransition = $this->params->get('resizetransition', '0');
		$initialwidth = $this->params->get('initialwidth', '320');
		$initialheight = $this->params->get('initialheight', '180');
		$defaultwidth = $this->params->get('defaultwidth', '640');
		$defaultheight = $this->params->get('defaultheight', '360');
		$vertioffset = $this->params->get('vertioffset', '0');
		$horizoffset = $this->params->get('horizoffset', '0');
		$showcaption = $this->params->get('showcaption', 'true');
		$showcounter = $this->params->get('showcounter', 'true');
		$usehtc = $this->params->get('usehtc', 'false');
		$attribtype = $this->params->get('attribtype', 'className');
		$attribname = $this->params->get('attribname', 'lightbox');

		
		
		

        // test, si on n'est pas bon on sort
        if (!in_array($id, $IDs) && $IDs[0] != 0)
            return false;

        /* fin de la fonction */
		
		$mainframe = & JFactory::getApplication();
        $document = & JFactory::getDocument();
        $doctype = $document->getType();
        JHTML::_('behavior.framework',true);
		$base = JURI::base();

        // si pas en frontend, on sort
        if ($mainframe->isAdmin()) {
            return false;
        }

        // si pas HTML, on sort
        if ($doctype !== 'html') {
            return;
        }
		
		// set transitions
		if ($resizetransition) $resizetransition = 'Fx.Transitions.'.$resizetransition;
		
		// check if IE rounded corners shall be forced
		$ieroundhtc = "";
		if ($usehtc) $ieroundhtc = "behavior: url(".$base."plugins/system/mediabox_ck/mediabox_ck/PIE.htc);";

        

        $document->addStyleSheet( 'plugins/system/mediabox_ck/mediabox_ck/mediaboxAdvBlack21.css' );
		$document->addStyleDeclaration("
			#mbCenter {
	background-color: ".$bgcolor.";
	-webkit-border-radius: ".$cornerradius."px;
	-khtml-border-radius: ".$cornerradius."px;
	-moz-border-radius: ".$cornerradius."px;
	border-radius: ".$cornerradius."px;
	-webkit-box-shadow: 0px ".$shadowoffset."px 20px rgba(0,0,0,0.50);
	-khtml-box-shadow: 0px ".$shadowoffset."px 20px rgba(0,0,0,0.50);
	-moz-box-shadow: 0px ".$shadowoffset."px 20px rgba(0,0,0,0.50);
	box-shadow: 0px ".$shadowoffset."px 20px rgba(0,0,0,0.50);
	/* For IE 8 */
	-ms-filter: \"progid:DXImageTransform.Microsoft.Shadow(Strength=".$shadowoffset.", Direction=180, Color='#000000')\";
	/* For IE 5.5 - 7 */
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=".$shadowoffset.", Direction=180, Color='#000000');
        ".$ieroundhtc."
	}
	
	#mbOverlay {
		background-color: ".$overlaycolor.";
	}
	
	#mbCenter.mbLoading {
		background-color: ".$bgcolor.";
	}
	
	#mbBottom {
		color: ".$text1color.";
	}
	
	#mbTitle, #mbPrevLink, #mbNextLink, #mbCloseLink {
		color: ".$text2color.";
	}
		");
        $document->addScript("plugins/system/mediabox_ck/mediabox_ck/mediaboxAdv-1.3.4b.js");
        $document->addScript("plugins/system/mediabox_ck/mediabox_ck/quickie.js");
        $document->addScriptDeclaration("
                    Mediabox.scanPage = function() {
                        var links = document.getElements(\"a\").filter(function(el) {
                            return el.".$attribtype." && el.".$attribtype.".test(/^".$attribname."/i);
                        });
                        links.mediabox({
                        overlayOpacity : 	".$overlayopacity.",
						resizeOpening : 	".$resizeopening.",
						resizeDuration : 	".$resizeduration.",
						resizeTransition : 	".$resizetransition.",
						initialWidth : 		".$initialwidth.",
						initialHeight : 	".$initialheight.",
						defaultWidth : 		".$defaultwidth.",
						defaultHeight : 	".$defaultheight.",
						vertioffset : 		".$vertioffset.",
						horizoffset : 		".$horizoffset.",
						showCaption : 		".$showcaption.",
						showCounter : 		".$showcounter.",
						attribType :		'".$attribtype."',
                        playerpath: '".$base."plugins/system/mediabox_ck/mediabox_ck/NonverBlaster.swf'
                        }, null, function(el) {
                            var rel0 = this.".$attribtype.".replace(/[[]|]/gi,\" \");
                            var relsize = rel0.split(\" \");
                            return (this == el) || ((this.".$attribtype.".length > ".strlen($attribname).") && el.".$attribtype.".match(relsize[1]));
                        });
                    };
                    window.addEvent(\"domready\", Mediabox.scanPage);
                    ");
        
    }

}

?>