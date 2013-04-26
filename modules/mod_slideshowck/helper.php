<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die;

class modSlideshowckHelper {

    /**
     * Get a list of the items.
     *
     * @param	JRegistry	$params	The module options.
     *
     * @return	array
     */
    static function getItems(&$params) {
        // Initialise variables.
        $db = JFactory::getDbo();
        $document = JFactory::getDocument();

        // load the libraries
        //jimport('joomla.application.module.helper');
        $items = json_decode(str_replace("|qq|", "\"", $params->get('slides')));
        foreach ($items as $i => $item) {
            if (!$item->imgname) {
                unset($items[$i]);
                continue;
            }
            
            if (stristr($item->imgname, "http")) {
                $item->imgthumb = $item->imgname;
            } else {
                // crée la miniature
                self::resizeImage($item->imgname, $params->get('thumbnailwidth','100'), $params->get('thumbnailheight','75'));
                // renomme le fichier
                $thumbext = explode(".", $item->imgname);
                $thumbext = end($thumbext);
                // set the variables
                $item->imgname = JURI::base().$item->imgname;
                $item->imgthumb = str_replace("." . $thumbext, "_th." . $thumbext, $item->imgname);
            }
            
            
            if ($item->imgvideo) $item->imgvideo = self::setVideolink($item->imgvideo);
        }
        //shuffle($items);
        return $items;
    }

    /**
     * Set the correct video link
     *
     * $videolink string the video path
     *
     * @return string the new video path
     */
    static function setVideolink($videolink) {
        // youtube
        if (stristr($videolink, 'youtu.be')) {
            $videolink = str_replace('youtu.be', 'www.youtube.com/embed', $videolink);
        }
        
        $videolink .= (stristr($videolink,'?')) ? '&wmode=transparent' : '?wmode=transparent';

        return $videolink;
    }

    /**
     * Create the list of all modules published as Object
     *
     * $file string the image path
     * $x integer the new image width
     * $y integer the new image height
     *
     * @return Boolean True on Success
     */
    static function resizeImage($file, $x, $y) {

        // $file = 'image.jpg' ; # L'emplacement de l'image à redimensionner. L'image peut être de type jpeg, gif ou png
        if (!$file)
            return;
        $file = JPATH_ROOT . DS . $file;
        //$x = 100;

        //$y = $x*3/4; # Taille en pixel de l'image redimensionnée

        $size = getimagesize($file);

        if ($size) {
            //echo 'Image en cours de redimensionnement...';
            // renomme le fichier
            $thumbext = explode(".", $file);
            $thumbext = end($thumbext);
            $thumbfile = str_replace("." . $thumbext, "_th." . $thumbext, $file);
            // var_dump($thumbfile);

            if (JFile::exists($thumbfile)) {
                $thumbsize = getimagesize($thumbfile);
                if ($thumbsize[0] == $x AND $thumbsize[1] == $y) {
                    //echo 'miniature existante';
                    return;
                }
            }


            if ($size['mime'] == 'image/jpeg') {
                $img_big = imagecreatefromjpeg($file); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);
                # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                        or $img_mini = imagecreate($x, $y);

                // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                imagejpeg($img_mini, $thumbfile);
            } elseif ($size['mime'] == 'image/png') {
                $img_big = imagecreatefrompng($file); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);
                # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                        or $img_mini = imagecreate($x, $y);

                // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                imagepng($img_mini, $thumbfile);
            } elseif ($size['mime'] == 'image/gif') {
                $img_big = imagecreatefromgif($file); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);
                # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y)
                        or $img_mini = imagecreate($x, $y);

                // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);

                imagegif($img_mini, $thumbfile);
            }
            //echo 'Image redimensionnée !';
        }
    }

}
