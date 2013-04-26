<?php
/**
 * @version		$Id: default.php 21020 2011-03-27 06:52:01Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	mod_cnj_jeux
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

?>
<div id="<?php echo $moduleclass_sfx; ?>" class="search-jeux-bloc titre-abel">
    
    <form action="<?php echo JRoute::_(Cnj_jeuxHelperRoute::getCnj_jeuxRoute());?> " method="post">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><label>Titre du jeu : </label></td>
                <td><label>Auteur : </label></td>
                <td><label>R&eacute;f&eacute;rences : </label></td>
                <td class="last"><label>Date de parution : </label></td>
            </tr>
            <tr>
                <td><input list="list_titre" type="text" name="search_titre" value="<?php echo $search_titre; ?>" />
                <datalist id="list_titre">
                <?php
                        $query_distinct_titre = 'SELECT titre FROM cnj_jeu order by titre where publication LIKE "publie"';
				$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_titre);
                        $distinct_titre = $db->loadRowList();			   
                        foreach($distinct_titre as $titre) :
                		  echo '<option value="'.$titre[0].'">';
                   		endforeach;
                ?>
                </datalist>
                </td>
     
               <td><input list="list_auteur" type="text" name="search_auteur" value="<?php echo $search_auteur; ?>" />
            	<datalist id="list_auteur">
                <?php
                        $query_distinct_auteur = 'SELECT nom FROM cnj_auteur order by nom';
				$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_auteur);
                        $distinct_auteur = $db->loadRowList();
           			foreach($distinct_auteur as $auteur) :
                		  echo '<option value="'.$auteur[0].'">';
                   		endforeach;
                ?>
                </datalist>
		</td>

                <td><input list="list_reference" type="text" name="search_reference" value="<?php echo $search_reference; ?>" /></td>
            	<datalist id="list_reference">
                <?php
                        $query_distinct_reference = 'SELECT nom FROM cnj_reference order by nom';
						$db = JFactory::getDBO();
                        $db->setQuery($query_distinct_reference);
                        $distinct_reference = $db->loadRowList();
                        foreach($distinct_reference as $reference) :
                		  echo '<option value="'.$reference[0].'">';
                   		endforeach;
                ?>
                </datalist>
  		  

          <td class="last"><input type="text" name="search_date_parution_debut" value="<?php echo $search_date_parution_debut; ?>" class="date" /> &agrave; <input type="text" name="search_date_parution_fin" value="<?php echo $search_date_parution_fin; ?>" class="date" /></td>
            </tr>
            <tr>
                <td><label>Mot cl&eacute; : </label></td>
                <td colspan="3" class="last"><label>Type de jeu : </label></td>
            </tr>
            <tr>
                <td><input list="list_motcle" type="text" name="search_motcle" value="<?php echo $search_motcle; ?>" />

            <datalist id="list_motcle">
                <?php
                        $db = JFactory::getDBO();
                        $db->setQuery('SELECT distinct motcle FROM cnj_motcle ');
                        $distinct_motcle = $db->loadRowList();
                        foreach($distinct_motcle as $motcle) :
                	    echo '<option value="'.$motcle[0].'">';
                   	endforeach;
               	        $db->setQuery('SELECT distinct mecanisme FROM cnj_mecanisme ');
                        $distinct_mecanisme = $db->loadRowList();
                        foreach($distinct_mecanisme as $mecanisme) :
                	    echo '<option value="'.$mecanisme[0].'">';
                   	endforeach; 
                    ?>
                </datalist>
    
		</td>
                <td colspan="3" class="last"><select name="search_type"><option value=""></option><option value="jeu_de_role"<?php echo ($search_type=='jeu_de_role'?' selected="selected"':''); ?>>Jeux de r&ocirc;le uniquement</option><option value="no_jeu_de_role"<?php echo ($search_type=='no_jeu_de_role'?' selected="selected"':''); ?>>Ne pas afficher les jeux de r&ocirc;le</option></select></td>
            </tr>
            <tr>
                <td colspan="4" align="right"><input type="submit" value="" class="submit" /></td>
            </tr>
        </table>
	<input type="hidden" name="task" value="category" />
	<input type="hidden" name="option" value="com_cnj_jeux" />
	<input type="hidden" name="act" value="search_jeu" />
    </form>
    
</div>
