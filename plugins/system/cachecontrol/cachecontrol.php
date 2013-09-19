<?php
/**
 * @package     CacheControl
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgSystemCachecontrol extends JPlugin
{
        private $caching = 0;
        
	function __construct( &$subject, $params )
        {
            parent::__construct($subject, $params);
        }
        
        function onAfterRoute(){
            
            if( $this->checkRules() && JFactory::getApplication()->isSite() ){
                $this->caching = JFactory::getConfig()->getValue('config.caching');
                JFactory::getConfig()->setValue('config.caching', 0);
            }
        }
        
        function onAfterDispatch(){
            if( $this->checkRules() && JFactory::getApplication()->isSite() ){
                $plugin = JPluginHelper::getPlugin('system', 'cachecontrol');
                jimport( 'joomla.html.parameter' );
                $pluginParams = new JParameter($plugin->params);
                if($pluginParams->def('reenable_afterdispatch', 0)){
                    JFactory::getConfig()->setValue('config.caching', $this->caching);
                }
            }
        }
        
        function checkRules(){
          
            $plugin = JPluginHelper::getPlugin('system', 'cachecontrol');
            jimport( 'joomla.html.parameter' );
            $pluginParams = new JParameter($plugin->params);
            $defs = str_replace("\r","",$pluginParams->def('definitions',''));
            $defs = explode("\n", $defs);
            
            foreach($defs As $def){
                $result = $this->parseQueryString($def);
                if(is_array($result)){
                    $found = 0;
                    $required = count($result);
                    foreach($result As $key => $value){
                        if( JRequest::getVar($key) == $value || ( JRequest::getVar($key, null) !== null && $value == '?' ) ){
                            $found++;
                        }
                    }
                    if($found == $required){
                        return true;
                    }
                }
            }
            
            return false;
        }
        
        function parseQueryString($str) {
            $op = array();
            $pairs = explode("&", $str);
            foreach ($pairs as $pair) {
                list($k, $v) = array_map("urldecode", explode("=", $pair));
                $op[$k] = $v;
            }
            return $op;
        } 
}
