<?php

/**
 * jfusion frontend controller
 *
 * PHP version 5
 *
 * @category  JFusion
 * @package   ControllerFront
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.application.component.view');

/**
 * JFusion Component Controller
 *
 * @category  JFusion
 * @package   ControllerFront
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
class JFusionControllerFrontEnd extends JController
{
    /**
     * Displays the integrated software inside Joomla without a frame
     *
     * @return string html
     */
    function displayplugin()
    {
        //find out if there is an itemID with the view variable
        $menuitemid = JRequest::getInt('Itemid');
        //we do not want the frontpage menuitem as it will cause a 500 error in some cases
        jimport('joomla.html.parameter');
        $jPluginParam = new JParameter('');
        //added to prevent a notice of $jview being undefined;
        $jview = null;
        if ($menuitemid && $menuitemid != 1) {
            $menu = & JMenu::getInstance('site');
            $item = & $menu->getItem($menuitemid);
            $menu_params = new JParameter($item->params, '');
            $jview = $menu_params->get('visual_integration', false);
            $JFusionPluginParam = $menu_params->get('JFusionPluginParam');
            if (empty($JFusionPluginParam)) {
                JError::raiseError('404', JText::_('ERROR_PLUGIN_CONFIG'));
            }
            //load custom plugin parameter
            $jPluginParamRaw = unserialize(base64_decode($JFusionPluginParam));
            $jPluginParam->loadArray($jPluginParamRaw);
            global $jname;
            $jname = $jPluginParam->get('jfusionplugin');
            if (isset($jPluginParamRaw[$jname]['params'])) {
            	$jPluginParam->loadArray($jPluginParamRaw[$jname]['params']);
				$jPluginParam->set('jfusionplugin',$jname);
            }
        }
        if (!empty($jview)) {
            //check to see if the plugin is configured properly
            $db = JFactory::getDBO();
            $query = 'SELECT status from #__jfusion WHERE name = ' . $db->Quote($jname);
            $db->setQuery($query);
            if ($db->loadResult() != 1) {
                //die gracefully as the plugin is not configured properly
                JError::raiseError('500', JText::_('ERROR_PLUGIN_CONFIG'));
            }
        } else {
            JError::raiseError('500', JText::_('NO_VIEW_SELECTED'));
        }
        //load the view
        $view = & $this->getView($jview, 'html');
        //parse required variables and render output
        if ($jview == 'wrapper') {
            //get the url
            $query = ($_GET);
            if (isset($query['jfile'])) {
                $jfile = $query['jfile'];
            } else {
                $jfile = 'index.php';
            }
            unset($query['option'], $query['jfile'], $query['Itemid'], $query['jFusion_Route']);
            $queries = array();
            foreach ($query as $key => $var) {
                $queries[] = $key . "=" . $var;
            }
            $wrap = $jfile . '?' . implode($queries, '&');
            $params2 = & JFusionFactory::getParams($jname);
            $source_url = $params2->get('source_url');
            //check for trailing slash
            if (substr($source_url, -1) == '/') {
                $url = $source_url . $wrap;
            } else {
                $url = $source_url . '/' . $wrap;
            }
            //set params
            $view->assignRef('url', $url);
            $view->assignRef('params', $menu_params);
        }
        //render the view
        $view->assignRef('jname', $jname);
        $view->assignRef('jPluginParam', $jPluginParam);
        $view->addTemplatePath(JPATH_COMPONENT . DS . 'view' . DS . strtolower($jview) . DS . 'tmpl');
        $view->setLayout('default');
        $view->display();
    }
}
