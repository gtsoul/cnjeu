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

jimport('joomla.application.component.controller');

require_once JPATH_SITE.DS.'components'.DS.'com_jvotesystem'.DS.'classes'.DS.'loader.php';

class jVoteSystemController extends JController
{
    /**
     * Method to display the view
     *
     * @access	public
     */
    function display()
    {
		//Create Submenu
		addSub( 'Overview', 'jvotesystem');
		addSub( 'Categories', 'categories');
		addSub( 'Boxen', 'boxen');
		addSub( 'Answers', 'answers', 'answers');
		addSub( 'Comments', 'comments', 'comments');
		addSub( 'Users', 'users', 'users');
		addSub( 'BBCodes', 'bbcodes', 'bbcodes', "generic");
		//Dateien laden
		VBUser::getInstance();
		VBParams::getInstance();
		
		$document = & JFactory::getDocument();
		$document->addStyleSheet(JURI::base(true).'/components/com_jvotesystem/assets/css/general.css');
		
		$loader = VBLoader::getInstance();
		$loader->loadLanguageFiles();
		
		
		parent::display();
    }// function

}// class
