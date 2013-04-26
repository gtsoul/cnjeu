<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

JText::script('MOD_SLIDESHOWCK_ADDSLIDE');
JText::script('MOD_SLIDESHOWCK_SELECTIMAGE');
JText::script('MOD_SLIDESHOWCK_CAPTION');
JText::script('MOD_SLIDESHOWCK_USETOSHOW');
JText::script('MOD_SLIDESHOWCK_IMAGE');
JText::script('MOD_SLIDESHOWCK_VIDEO');
JText::script('MOD_SLIDESHOWCK_IMAGEOPTIONS');
JText::script('MOD_SLIDESHOWCK_LINKOPTIONS');
JText::script('MOD_SLIDESHOWCK_VIDEOOPTIONS');
JText::script('MOD_SLIDESHOWCK_ALIGNEMENT_LABEL');
JText::script('MOD_SLIDESHOWCK_TOPLEFT');
JText::script('MOD_SLIDESHOWCK_TOPCENTER');
JText::script('MOD_SLIDESHOWCK_TOPRIGHT');
JText::script('MOD_SLIDESHOWCK_MIDDLELEFT');
JText::script('MOD_SLIDESHOWCK_CENTER');
JText::script('MOD_SLIDESHOWCK_MIDDLERIGHT');
JText::script('MOD_SLIDESHOWCK_BOTTOMLEFT');
JText::script('MOD_SLIDESHOWCK_BOTTOMCENTER');
JText::script('MOD_SLIDESHOWCK_BOTTOMRIGHT');
JText::script('MOD_SLIDESHOWCK_LINK');
JText::script('MOD_SLIDESHOWCK_TARGET');
JText::script('MOD_SLIDESHOWCK_SAMEWINDOW');
JText::script('MOD_SLIDESHOWCK_NEWWINDOW');
JText::script('MOD_SLIDESHOWCK_VIDEOURL');
JText::script('MOD_SLIDESHOWCK_REMOVE');
JText::script('MOD_SLIDESHOWCK_IMPORTFROMFOLDER');

class JFormFieldCkslidesmanager extends JFormField {

    protected $type = 'ckslidesmanager';

    protected function getInput() {

        $document = JFactory::getDocument();
        $document->addScriptDeclaration("JURI='" . JURI::root() . "'");
        $path = 'modules/mod_slideshowck/elements/ckslidesmanager/';
        JHTML::_('behavior.modal');
        JHTML::_('script', 'ckslidesmanager.js', $path);
        JHTML::_('script', 'FancySortable.js', $path);
        JHTML::_('stylesheet', 'ckslidesmanager.css', $path);

        $html = '<input name="' . $this->name . '" id="ckslides" type="hidden" value="' . $this->value . '" />'
                . '<input name="ckaddslide" id="ckaddslide" type="button" value="' . JText::_('MOD_SLIDESHOWCK_ADDSLIDE') . '" onclick="javascript:addslideck();"/>'
                //.'<input name="ckaddfromfolder" id="ckaddfromfolder" type="button" value="Import from a folder" onclick="javascript:addfromfolderck();"/>'
                //.'<input name="ckstoreslide" id="ckstoreslide" type="button" value="Save" onclick="javascript:storeslideck();"/>'
                . '<ul id="ckslideslist" style="clear:both;"></ul>';

        return $html;
    }

    protected function getPathToImages() {
        $localpath = dirname(__FILE__);
        $rootpath = JPATH_ROOT;
        $httppath = trim(JURI::root(), "/");
        $pathtoimages = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
        return $pathtoimages;
    }

    protected function getLabel() {

        return '';
    }

    protected function getArticlesList() {
        $db = & JFactory::getDBO();

        $query = "SELECT id, title FROM #__content WHERE state = 1 LIMIT 2;";
        $db->setQuery($query);
        $row = $db->loadObjectList('id');
        var_dump($row);
        return json_encode($row);
    }

}

