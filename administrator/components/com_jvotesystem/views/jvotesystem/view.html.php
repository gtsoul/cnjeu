<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

//-- No direct access
defined('_JEXEC') or die('=;)');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the jVoteSystem Component
 *
 * @package    jVoteSystem
 * @subpackage Views
 */

class jVoteSystemViewjVoteSystem extends JView
{
    /**
     * jVoteSystem view display method
     *
     * @return void
     **/
    function display($tpl = null)
    {
		$this->charts =& VBCharts::getInstance();
		$this->general =& VBGeneral::getInstance();
		
		$this->charts->addchartjs('corechart');
		//Load pane behavior
		jimport('joomla.html.pane');

		//initialise variables
		$document	= & JFactory::getDocument();
		$pane   	= & JPane::getInstance('sliders');
		$user 		= & JFactory::getUser();
		
		/* Loads a update script by www.joomess.de - Asynchron*/
		$js = '(function()
		{
			var po = document.createElement("script");
			po.type = "text/javascript"; po.async = true;po.src = "http://joomess.de/index.php?option=com_je&view=tools&id=1&task=script&version=2.05&url='.urlencode(JUri::current()).'";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(po, s);
		})();';
		$document->addScriptDeclaration($js);
				
		//build Toolbar
		if ($user->get('gid') > 24 OR !version_compare( JVERSION, '1.6.0', 'lt' )) 
			JToolBarHelper::preferences('com_jvotesystem', 500);
			
		//IE-Meldung
		$check = strstr($_SERVER["HTTP_USER_AGENT"], "IE");
		if($check) {
			$js = ' alert(" DE: Das Backend ist für Mozilla Firefox geschrieben worden. Beim Internet Explorer können Fehler auftreten! \n EN: The backend was written for Mozilla Firefox. In Internet Explorer, errors may occur!"); ';
			$document->addScriptDeclaration( $js );
		}
		
		//assign vars to the template
		$this->assignRef('pane'			, $pane);
		$this->assignRef('user'			, $user);
		
        parent::display($tpl);
    }//function
	
	function quickiconButton( $link, $image, $text, $modal = 0, $target="")
	{
		//initialise variables
		$lang 		= & JFactory::getLanguage();
  		?>

		<div id="cpanel" style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<?php
				if ($modal == 1) {
					JHTML::_('behavior.modal');
				?>
					<a href="<?php echo $link.'&amp;tmpl=component'; ?>" style="cursor:pointer" <?php if($target != "") echo 'target="'.$target.'"'; ?> class="modal" rel="{handler: 'iframe', size: {x: 875, y: 650}, closable: false, closeBtn: false, onOpen: function(){jVSQuery('#sbox-btn-close').remove();jVSQuery('object').hide();}, onClose: function() {jVSQuery('object').show();}}">
				<?php
				} else {
				?>
					<a href="<?php echo $link; ?>" <?php if($target != "") echo 'target="'.$target.'"'; ?> >
				<?php
				}

					echo JHTML::_('image', 'administrator/components/com_jvotesystem/assets/images/'.$image, $text );
				?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}

}//class
