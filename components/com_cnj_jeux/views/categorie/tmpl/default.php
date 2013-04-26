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

function format_text ($text) {
    $text = mb_strtoupper($text);
    return $text;
}

$limitstart = JRequest::getVar('limitstart');

?>
<div class="item-page jeu">
    
    <h2>Conservatoire</h2>
    <h3><?php echo $this->document->title; ?></h3>
    
    <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && (is_object($this->pagination))) : ?>
        <?php if ($this->pagination->get('pages.total') > 1) : ?>
            <div class="pagination">
                <?php  if ($this->params->def('show_pagination_results', 1)) : ?>
                    <?php echo $this->pagination->getPagesCounter(); ?><br />
                <?php endif; ?>
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    
    <div id="resultats">

        <?php 
            if(count($this->items) == 0) echo '<p>Aucun jeu n\'a &eacute;t&eacute; trouv&eacute;. Veuillez modifier votre recherche.</p>';
            else {
                $i=0;
                foreach ($this->items as $item) {
                    echo ($i%3==0?'<div class="row">':'');
                    echo '<div class="fiche'.($i%3==2?' last':'').'">';


                    if($item->path_miniature && is_readable($item->path_miniature)) {
                        echo '<table cellpadding="0" cellspacing="0" border="0" class="visuel"><tr><td>';
                        echo JHtml::_('image',$item->path_miniature, format_text($item->titre), array());
                        echo '</td></tr></table>';
                    }

                    echo '<div class="info">';
                    if($item->titre) echo '<p><span class="titre">Titre : </span>' . format_text($item->titre) . '</p>';
                    if(count($item->references)>0) {
                        $txt = ''; $sep = ', ';
                        echo '<p><span class="titre">R&eacute;f&eacute;rences : </span>';
                        foreach($item->references as $ref) {
                            $txt .= format_text($ref->nom) . $sep;
                        }
                        echo substr($txt, 0, -2);
                        echo '</p>';
                    }
                    if(count($item->auteurs)>0) {
                        $txt = ''; $sep = ', ';
                        echo '<p><span class="titre">Auteurs : </span>';
                        foreach($item->auteurs as $auteur) {
                            $txt .= format_text($auteur->nom) . $sep;
                        }
                        echo substr($txt, 0, -2);
                        echo '</p>';
                    }
                    if($item->date_parution_debut || $item->date_parution_fin) echo '<p><span class="titre">Date de parution : </span>' . format_text($item->date_parution_debut . ($item->date_parution_debut && $item->date_parution_fin ? ' - ' : '') . $item->date_parution_fin, false) . '</p>';
                    if($item->pays_edition) echo '<p><span class="titre">Version : </span>' . format_text($item->pays_edition) . '</p>';
                    echo '<a href="' . JRoute::_('index.php?option=com_cnj_jeux&view=fiche&code='.$item->id_jeu.(isset($limitstart)?'&limitstart='.$limitstart:'')) . '" class="en_savoir_plus">En savoir plus</a>';
                    echo '</div>';
                    echo '</div>';
                    echo ($i%3==2?'</div>':'');
                    $i++;
                }
            }
        ?>

        <div class="clear"></div>
    </div>
    
    
    <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && (is_object($this->pagination))) : ?>
        <?php if ($this->pagination->get('pages.total') > 1) : ?>
            <div class="pagination">
                <?php  if ($this->params->def('show_pagination_results', 1)) : ?>
                    <?php echo $this->pagination->getPagesCounter(); ?><br />
                <?php endif; ?>
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
</div>

<script>
$(window).load(function(){

    if($(document).find('.row').length > 0)
    {
        $('.row').each(function() {
            var hauteur = 0;
            $(this).find('.fiche').each(function() { 
                if($(this).height() > hauteur) hauteur = $(this).height();
            });
            
            $(this).find('.fiche').each(function() { 
                var previous_hauteur = $(this).height();
                $(this).css('height', hauteur);
                $(this).find('.en_savoir_plus').css('margin-top', hauteur - previous_hauteur + 10);
            });
        });
    }
});
</script>
