<?php

/**
 * This is view file for cpanel
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsFront
 * @subpackage Wrapper
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript">
    function getElement(aID)
    {
        return (document.getElementById) ?
            document.getElementById(aID) : document.all[aID];
    }

    function getIFrameDocument(aID)
    {
        var rv = null;
        var frame=getElement(aID);
        // if contentDocument exists, W3C compliant (e.g. Mozilla)

        if (frame.contentDocument)
            rv = frame.contentDocument;
        else if (frame.contentWindow)
            rv = frame.contentWindow.document;        
        else // bad IE  ;)
            rv = document.frames[aID].document;
        return rv;
    }

    function adjustMyFrameHeight()
    {
        var frame = getElement("blockrandom");
        var frameDoc = getIFrameDocument("blockrandom");
        frame.height = frameDoc.body.offsetHeight;
    }

    function scrollMeUp() {
        window.scroll(0,0);
    }


</script>
<div class="contentpane">
<iframe

<?php 
	$onload = " onload=";
	if ($this->params->get('wrapper_autoheight', 1)) {
		$onload .= "adjustMyFrameHeight();";
	}
	if ($this->params->get('wrapper_scrolltotop', 1)) {
		$onload .= "scrollMeUp();";
	}
echo $onload;
 ?>

id="blockrandom"
name="iframe"
src="<?php echo $this->url; ?>"
width="<?php echo $this->params->get('wrapper_width', '100%'); ?>"
height="<?php echo $this->params->get('wrapper_height', '500'); ?>"

<?php if ($this->params->get('wrapper_transparency')) { ?>
    allowtransparency="true"
    <?php
} else { ?>
    allowtransparency="false"
    <?php
} ?>

style="vertical-align:top;border-style:none;overflow:<?php echo $this->params->get('wrapper_scroll', 'auto'); ?>;" class="wrapper">
<?php echo JText::_('OLD_BROWSER'); ?>
</iframe>
</div>