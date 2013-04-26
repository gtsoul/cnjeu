<?php
/**
 * @package Component jVoteSystem for Joomla! 1.5 - 2.5
 * @projectsite www.joomess.de/projects/jvotesystem
 * @authors Johannes Meßmer, Andreas Fischer
 * @copyright (C) 2010 - 2012 Johannes Meßmer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class plgButtonjVoteSystemButton extends JPlugin
{
   function plgButtonjVoteSystemButton(&$subject, $config) {
      parent::__construct($subject, $config);
   }

   function onDisplay($name) {
      global $mainframe;
	  $app = JFactory::getApplication();
      $document = & JFactory::getDocument();
		
		$link = "components/com_jvotesystem/assistant/index.php?view=button&interface=".($app->isAdmin() ? "administrator" : "site");
		static $loadOnce;
		$js = "function insertjVoteSystemPoll(id, title) {
			jInsertEditorText('{jvotesystem poll=|'+ id +'|}', '".$name."');
			try {
				document.getElementById('sbox-window').close();
			} catch(err) {
				SqueezeBox.close();
			}
		}
		window.addEvent('domready', function() {
			$$('.button2-left .jvotesystem a').set('href','".JURI::root().$link."');
		});";
      
		if(empty($loadOnce)) {
			$document->addScriptDeclaration($js);
			$loadOnce = true;
		}
      
	  $document->addStyleSheet(JURI::root( true ).'/plugins/editors-xtd/jvotesystembutton/css/jvotesystem.css');
      JHTML::_('behavior.modal');

      $button = new JObject();
      $button->set('modal', true);
      $button->set('text', 'jVoteSystem');
      $button->set('name', 'jvotesystem');
	  $button->set('options', "{handler: 'iframe', size: {x: 550, y: 400}}");
	  $button->set('link', $link);
	  
      return $button;
   }
}