<?php
/**
 * @version		$Id: default.php 21518 2011-06-10 21:38:12Z chdemko $
 * @package		Joomla.Site
 * @subpackage	com_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$baseurl = JURI::base();

function format_text ($text, $majuscule = true) {
    if($majuscule) $text = mb_strtoupper($text);
    return $text;
}

$limitstart = JRequest::getVar('limitstart');
$item = $this->item;




?>
<div class="item-page fiche_detail">

    <h2>Conservatoire</h2>
    <h3><?php echo $this->document->title; ?></h3>
    
    <!--a href="<?php echo JRoute::_('index.php?option=com_cnj_jeux&view=categorie'.(isset($limitstart)?'&limitstart='.$limitstart:'')); ?>" class="retour">retour vers la liste des r&eacute;sultats <?php echo JHtml::_('image', $baseurl . '/templates/lch_cnj/images/btn-next.png', '', array()); ?></a-->

<a href="javascript:history.go(-1)"	 class="retour">retour vers la liste des r&eacute;sultats <?php echo JHtml::_('image', $baseurl . '/templates/lch_cnj/images/btn-next.png', '', array()); ?></a>





 <div class="description">
        <?php 
            if((count($item->visuels)>0 && is_readable($item->visuels[0]->path_optimise)) || ($item->disponibilite_regle && utf8_decode($item->disponibilite_regle) == 'Nous recherchons la règle')) {
                echo '<div class="visuel">';
                echo '<ul id="slider">';
                foreach($item->visuels as $visuel) {
                    $size = getimagesize($visuel->path_optimise);
                    echo '<li>' . JHtml::_('image', $visuel->path_optimise, format_text($item->titre), array('width' => ($size[0]<494?$size[0]:494))) . '</li>';
                }
                echo '</ul>';
                
                if($item->disponibilite_regle && utf8_decode($item->disponibilite_regle) == 'Nous recherchons la règle') {
                    echo '<div class="pictos">';
                    switch(utf8_decode($item->disponibilite_regle)) {
                        /*case 'Nous avons la règle en': 
                        case 'Nous avons la règle en ; Nous recherchons la règle': 
                        case 'Nous recherchons la règle ; Nous avons la règle en': 
                            echo JHtml::_('image', $baseurl . '/templates/lch_cnj/images/picto-regles.png', '', array()) . 'Nous avons la r&egrave;gle';
                            break;*/
                        case 'Nous recherchons la règle': 
                            echo JHtml::_('image', $baseurl . '/templates/lch_cnj/images/picto-regles-recherche.png', '', array()) . 'Nous recherchons la r&egrave;gle';
                            break;
                        default : break;
                    }
                    echo '</div>';
                }
                echo '</div>';
            }

            echo '<div class="info">';
            
            if($item->titre) echo '<p><span class="titre">Titre : </span>' . format_text($item->titre) . '</p>';
            
            if(count($item->references)>0) {
                $txt = ''; $sep = ', '; $prefix = '';
                echo '<p><span class="titre">R&eacute;f&eacute;rences : </span>';
                if(count($item->references)>1) {
                    echo '<br />'; $sep = '<br />'; $prefix = '&nbsp;&nbsp;&nbsp;';
                }
                foreach($item->references as $ref) {
                    $txt .= ($ref->nom ? $prefix . format_text($ref->nom . ($ref->qualite?' (' . $ref->qualite . ')':'')) . $sep : '');
                }
                echo substr($txt, 0, -strlen($sep));
                echo '</p>';
            }
            
            if(count($item->auteurs)>0) {
                $txt = ''; $sep = ', '; $prefix = '';
                echo '<p><span class="titre">Auteurs : </span>';
                if(count($item->auteurs)>1) {
                    echo '<br />'; $sep = '<br />'; $prefix = '&nbsp;&nbsp;&nbsp;';
                }
                /*foreach($item->auteurs as $auteur) {
                    $txt .= ($auteur->nom ? $prefix . format_text($auteur->nom . ($auteur->qualite?' (' . $auteur->qualite . ')':($auteur->qualite_old?' (' . $auteur->qualite_old . ')':''))) . $sep : '');
                }*/
                foreach($item->auteurs as $auteur) {
                	if($auteur->qualite)
                	{
                		$txt .= "&nbsp;&nbsp;&nbsp;".format_text($auteur->nom)." en tant que ".format_text($auteur->qualite);
                	}
                	elseif($auteur->qualite_old)
                	{
                		$txt .= "&nbsp;&nbsp;&nbsp;".format_text($auteur->nom)." en tant que ".format_text($auteur->qualite_old);
                	}
                	else
                	{
                		$txt .= "&nbsp;&nbsp;&nbsp;".format_text($auteur->nom);
                	}
                	$txt .= $sep;
                	                  
                }
                echo substr($txt, 0, -strlen($sep));
                echo '</p>';
            }
            
            if(isset($item->type) && $item->type) echo '<p><span class="titre">Type de jeu : </span>' . format_text($item->type, false) . '</p>';
            if(isset($item->numero_inventaire_editeur) && $item->numero_inventaire_editeur) echo '<p><span class="titre">N&deg; d\'inventaire &eacute;diteur : </span>' . format_text($item->numero_inventaire_editeur, false) . '</p>';
            if(isset($item->gamme_jeu) && $item->gamme_jeu) echo '<p><span class="titre">Gamme &eacute;diteur : </span>' . format_text($item->gamme_jeu) . '</p>';
            if((isset($item->date_parution_debut) && $item->date_parution_debut) || (isset($item->date_parution_fin) && $item->date_parution_fin)) echo '<p><span class="titre">Date de parution : </span>' . format_text($item->date_parution_debut . ($item->date_parution_debut && $item->date_parution_fin ? ' - ' : '') . $item->date_parution_fin, false) . '</p>';
            if(isset($item->information_date) && $item->information_date) echo '<p><span class="titre">Information date : </span>' . format_text($item->information_date, false) . '</p>';
            if(isset($item->pays_edition) && $item->pays_edition) echo '<p><span class="titre">Version : </span>' . format_text($item->pays_edition) . '</p>';
            if(isset($item->type_materiel) && $item->type_materiel) echo '<p><span class="titre">Type de mat&eacute;riel : </span>' . format_text($item->type_materiel, false) . '</p>';
            if(isset($item->transfert_nb_joueurs) && $item->transfert_nb_joueurs) echo '<p><span class="titre">Nb de joueurs : </span>' . format_text($item->transfert_nb_joueurs, false) . '</p>';
            if(isset($item->age_indique) && $item->age_indique) echo '<p><span class="titre">&Acirc;ge indiqu&eacute; : </span>' . format_text($item->age_indique, false) . '</p>';
            if(isset($item->temps_partie) && $item->temps_partie) echo '<p><span class="titre">Temps de la partie : </span>' . format_text($item->temps_partie, false) . '</p>';
            if(isset($item->contenu_jeu) && $item->contenu_jeu) echo '<p><span class="titre">Contenu du jeu : </span>' . format_text($item->contenu_jeu, false) . '</p>';
            /*if(isset($item->transfert_mot_cle) && $item->transfert_mot_cle) echo '<p><span class="titre">Mots cl&eacute;s : </span>' . format_text($item->transfert_mot_cle,false).'</p>';
            if(isset($item->mecanisme) && $item->mecanisme) echo '<p><span class="titre">M&eacute;canismes : </span>' . format_text($item->mecanisme,false).'</p>';
            
            if(isset($item->resume) && $item->resume) echo '<p><span class="titre">R&eacute;sum&eacute; : </span><br />' . format_text($item->resume, false) . '</p>';*/

if(count($item->motcles)>0) {
                echo '<p><span class="titre">Mots cl&eacute;s : </span>';
                $txt = ''; $sep = ', '; $prefix = '';
                foreach($item->motcles as $motcle) {
                        $txt .= format_text($motcle->motcle);
                        $txt .= $sep;
                }
                echo substr($txt, 0, -strlen($sep));
                echo '</p>';
            }

            if(count($item->mecanismes)>0) {
                echo '<p><span class="titre">M&eacute;canismes : </span>';
                $txt = ''; $sep = ', '; $prefix = '';
                foreach($item->mecanismes as $mecanisme) {
                        $txt .= format_text($mecanisme->mecanisme);
                        $txt .= $sep;
                }
                echo substr($txt, 0, -strlen($sep));
                echo '</p>';
            }

            
            if(count($item->documents)>0) {
                $txt = ''; $sep = ', '; $prefix = '';
                echo '<br /><p><span class="titre">Documents : </span>';
                if(count($item->documents)>1) {
                    echo '<br />'; $prefix = '&nbsp;&nbsp;&nbsp;';
                }
                foreach($item->documents as $doc) {
                    $txt .= ($doc->nom && $doc->path_hd ? $prefix . '<a href="' . $doc->path_hd . '" target="_blank" class="telecharger">' . format_text($doc->nom, false) . '</a>' : '');
                }
                echo $txt;
                echo '</p>';
            }
            
            echo '</div>';
                    ?>
    </div>       
</div>

<script type="text/javascript" >
$(document).ready(function(){	
    $('#slider').bxSlider({
        controls: false,
        pager: true,
        buildPager: function(slideIndex){
            switch (slideIndex){
                <?php 
                    $i=0;
                    foreach($item->visuels as $visuel) {
                    	$path_parts = pathinfo($visuel->nom);
                        echo 'case ' . $i . ':';
                        echo 'return \'<a href=""><img src="'.$visuel->path_optimise.'" /></a>'.$path_parts['filename'].'&nbsp;&nbsp;&nbsp;&nbsp;\';';
                        $i++; 
                    }
                ?>
            }
        }
    });
});
</script>
