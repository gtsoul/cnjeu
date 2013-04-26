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

<div class"slideshow">
    
    <ul id="slider1">
        <?php foreach($list as $item): ?>
            <?php
            $images = json_decode($item->images);
            $urls = json_decode($item->urls);
            $url = $urls->urla;
            $visuel = $images->image_intro;
            ?>
        <li>
            <div class="item">
                <div class="bloc-texte">
                    <p class="titre">
                    <?php if ($url != ""){echo "<a href=\"$url\">";}
                    echo $item->title;
                    if ($url != ""){echo "</a>";} ?>
                    </p>
                    <div class="texte">
                        <?php echo $item->introtext;?> 
                    </div>
                    <?php if ($url != ""){echo "<p class=\"lire-suite\"><a href=\"$url\">Lire la suite</a></p>";} ?>
                </div>
                <div class="visuel">
                    <img src="<?php echo $visuel ?>" alt="<?php echo $item->title; ?>" />
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
    
<script type="text/javascript">
    $(document).ready(function(){
        $('#slider1').bxSlider({
            controls: false,
            auto: true,
            pause: 8000,
            pager: true
        });
    });
</script>
    
</div>
