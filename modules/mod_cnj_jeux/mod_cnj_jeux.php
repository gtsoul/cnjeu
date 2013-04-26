<?php

// no direct access
defined('_JEXEC') or die;

// include the component cnj_jeux functions only once
require_once JPATH_SITE.'/components/com_cnj_jeux/helpers/route.php';

// include the module cnj_jeux functions only once
require_once dirname(__FILE__).'/helper.php';

$start                  = urldecode(JRequest::getString('start'));
$limitstart             = urldecode(JRequest::getString('limitstart'));
$act                    = urldecode(JRequest::getString('act'));
$search_titre		= urldecode(JRequest::getString('search_titre'));
$search_auteur          = urldecode(JRequest::getString('search_auteur'));
$search_reference       = urldecode(JRequest::getString('search_reference'));
$search_date_parution_debut = urldecode(JRequest::getString('search_date_parution_debut'));
$search_date_parution_fin   = urldecode(JRequest::getString('search_date_parution_fin'));
$search_motcle		= urldecode(JRequest::getString('search_motcle'));
$search_type          = urldecode(JRequest::getString('search_type'));
$moduleclass_sfx	= 	htmlspecialchars($params->get('moduleclass_sfx'));

if(!empty($start) || $limitstart!='') {
    $session = JFactory::getSession();
    $search_titre = $session->get('search_titre');
    $search_auteur = $session->get('search_auteur');
    $search_reference = $session->get('search_reference');
    $search_date_parution_debut = $session->get('search_date_parution_debut');
    $search_date_parution_fin = $session->get('search_date_parution_fin');
    $search_motcle = $session->get('search_motcle');
    $search_type = $session->get('search_type');
}

require(JModuleHelper::getLayoutPath('mod_cnj_jeux'));

?>