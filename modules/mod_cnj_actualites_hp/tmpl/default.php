<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_custom
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="top-bloc-actualite"><img src="templates/lch_cnj/images/top-bloc-actualite.gif" alt="" /></div>
<div class="bloc-actualite">
    <p class="titre"><?php echo $titreModule; ?></p>
    <ul>
       <?php foreach($list as $item): ?>
        <li>
            <a href="<?php echo $item->link; ?>">
                <span class="titre-actu"><?php echo $item->title; ?></span><br />
                <span class="texte"><?php echo $item->introtext; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <p class="lien-actualites"><a href="index.php?option=com_content&view=category&layout=blog<?php echo $getVarSuffix;?>">Toute l'actualité</a></p>
</div>
<div class="bottom-bloc-actualite"><img src="templates/lch_cnj/images/bottom-bloc-actualite.gif" alt="" /></div>