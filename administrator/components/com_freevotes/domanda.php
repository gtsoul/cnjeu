<?php

/*
* @name freevote 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

echo"
<style>
	#toolbar-box, #border-top, #header-box, #footer{display:none;}
	#content-box, .submenu-box, div.m, body, html{border:none; width:220px;}
</style>
<form method='post' action='index.php?option=com_freevotes&id=".$_GET['id']."' target='_parent'>
	".$_GET['nome']." <input type='text' name='nome_domanda' value='".$_GET['valore1']."'>
	<input type='submit' value='".$_GET['salva']."'>
</form>";