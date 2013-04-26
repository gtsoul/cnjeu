<?php

/*
* @name freevote 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

	if($_GET['valore2']==""){
		$r=dechex(rand(0,255)); if(strlen($r)<2){$r.=0;}
		$g=dechex(rand(0,255)); if(strlen($g)<2){$g.=0;}
		$b=dechex(rand(0,255)); if(strlen($b)<2){$b.=0;}
		$rgb="#".$r.$g.$b;
	}
	else{$rgb="#".$_GET['valore2'];}

echo"
<style>
	#toolbar-box, #border-top, #header-box, #footer{display:none;}
	#content-box, .submenu-box, div.m, body, html{border:none; width:220px;}
</style>
<table>
	<form method='post' action='index.php?option=com_freevotes&view=risposte&id=".$_GET['id']."' target='_parent'>
		<tr><td>".$_GET['nome']."</td><td><input type='text' name='nome_risposta' value='".$_GET['valore1']."'></td></tr>
		<tr><td>".$_GET['colore']."</td><td><input type='text' name='colore' id='colore' value='".$rgb."'> <span id='genera' style='border:1px solid #000; cursor:pointer; padding:4px;'>Genera</span></td></tr>
		<tr><td>".$_GET['domande']."</td><td><select name='seleziona_domande'>";
		
		$righe=explode("<riga>",$_GET['elenco_domande']);
		foreach($righe as $riga)
		{
			$cella=explode("<cella>",$riga);
			if($_GET['valore3']==$cella[0]){$sel="selected='selected'";}else{$sel="";}
			echo "<option ".$sel." value='".$cella[0]."'>".$cella[1]."</option>";
		}
		
		echo"
		</select></td></tr>
		<tr><td><input type='submit' value='".$_GET['salva']."'></td><td></td></tr>
	</form>
	</table>
<br /><div id='colore_prew' style='width:30px; height:30px; background:".$rgb.";'></div>";
?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
$("#colore").keyup(function(){
	$("#colore_prew").css("background",$(this).val());
});
$("#genera").click(function(){
	var r=Math.ceil(Math.random()*255);
	var g=Math.ceil(Math.random()*255);
	var b=Math.ceil(Math.random()*255);
	$("#colore").val("#"+r.toString(16)+g.toString(16)+b.toString(16));
	$("#colore_prew").css("background","#"+r.toString(16)+g.toString(16)+b.toString(16));
});
</script>