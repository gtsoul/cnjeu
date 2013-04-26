<?php
/*
* @name freevote 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

echo"<style>iframe{overflow:hidden;}</style>";
if($_GET['task']=="domanda"){
	include("domanda.php");
}
else if($_GET['task']=="risposta"){
	include("risposta.php");
}
else
{
	$domande=JText::_('COM_FREEVOTES_DOMANDE');
	$risposte=JText::_('COM_FREEVOTES_RISPOSTE');
	$inserisci_domanda=JText::_('COM_FREEVOTES_INSERISCI_DOMANDA');
	$inserisci_risposta=JText::_('COM_FREEVOTES_INSERISCI_RISPOSTA');
	$nome=JText::_('COM_FREEVOTES_NOME');
	$free_votes=JText::_('COM_FREEVOTES_FREE_VOTES');
	$salva=JText::_('COM_FREEVOTES_SALVA');
	$colore=JText::_('COM_FREEVOTES_COLORE');
	$cancella=JText::_('COM_FREEVOTES_CANCELLA');
	$cerca=JText::_('COM_FREEVOTES_CERCA');
	$genera=JText::_('COM_FREEVOTES_GENERA');

	$app =& JFactory::getApplication();
	$template_name = $app->getTemplate();
	$database =& JFactory::getDBO();

	JToolBarHelper::title($free_votes);

		//per cambiar l'immagine dare il nome di una presente nella cartella administrator/templates/bluestork/images/toolbar
		if($_GET['view']=="domande" || $_GET['view']==""){
			$bar=& JToolBar::getInstance( 'toolbar' )->appendButton( 'Popup', 'new', $inserisci_domanda, 'index.php?option=com_freevotes&task=domanda&nome='.$nome.'&salva='.$salva, 300, 80 );
		}
		if($_GET['view']=="risposte"){
			$query = "SELECT * FROM #__free_votes_domande";
			$database->setQuery($query);
			$results = $database->loadAssocList();
			$elenco_dom="";
			foreach($results as $result)
			{
				$elenco_dom.=$result['id']."<cella>".$result['nome']."<riga>";
			}
		
			$bar=& JToolBar::getInstance( 'toolbar' )->appendButton( 'Popup', 'new', $inserisci_risposta, 'index.php?option=com_freevotes&task=risposta&nome='.$nome.'&salva='.$salva.'&colore='.$colore.'&domande='.$domande.'&genera='.$genera.'&elenco_domande='.$elenco_dom, 300, 200 );
		}
	 
		JSubMenuHelper::addEntry(
			$domande,
			'index.php?option=com_freevotes&view=domande'
		);
		JSubMenuHelper::addEntry(
			$risposte,
			'index.php?option=com_freevotes&view=risposte'
		);

	if($_POST['nome_domanda']!="")
	{
		if($_GET['id']!=""){
			$query='UPDATE #__free_votes_domande SET nome="'.htmlentities($_POST['nome_domanda'],ENT_QUOTES).'" WHERE id='.$_GET['id'];
		}
		else{
			$query="INSERT INTO #__free_votes_domande (nome) VALUES ('$_POST[nome_domanda]')";
		}
		$database->setQuery($query);
		$results=$database->query();
	}
	if($_POST['nome_risposta']!="" && $_POST['seleziona_domande']!="" && $_POST['colore']!="")
	{
		if($_GET['id']!=""){
			$query='UPDATE #__free_votes_risposte SET nome="'.htmlentities($_POST['nome_risposta'],ENT_QUOTES).'", colore="'.htmlentities($_POST['colore'],ENT_QUOTES).'", domanda="'.$_POST['seleziona_domande'].'" WHERE id='.$_GET['id'];
		}
		else{
			$query="INSERT INTO #__free_votes_risposte (nome, colore, domanda) VALUES ('".htmlentities($_POST[nome_risposta],ENT_QUOTES)."', '".htmlentities($_POST[colore],ENT_QUOTES)."', $_POST[seleziona_domande])";
		}
		$database->setQuery($query);
		$results=$database->query();
	}
	if($_GET['cancella_domanda']!="")
	{
		$query="DELETE FROM #__free_votes_domande WHERE id=".$_GET['cancella_domanda'];
		$database->setQuery($query);
		$results=$database->query();
	}
	if($_GET['cancella_risposta']!="")
	{
		$query="DELETE FROM #__free_votes_risposte WHERE id=".$_GET['cancella_risposta'];
		$database->setQuery($query);
		$results=$database->query();
	}
	if($_GET['view']=="domande" || $_GET['view']=="")
	{
		$query = "SELECT * FROM #__free_votes_domande";
		$database->setQuery($query);
		$results = $database->loadAssocList();

		$i=1;
		echo"<table class='adminlist'><thead><tr><td><strong>Id</strong></td><td><strong>".$domande."</strong></td><td><strong>".$cancella."</strong></td></tr></thead>";
		foreach($results as $result)
		{
			if($i%2==0){$row="row0";}
			else{$row="row1";}
			echo "<tr class='".$row."'><td>".$result['id']."</td><td>
			<a class='modal' rel='{handler: \"iframe\", size: {x: 300, y: 200}, onClose: function() {}}' href='index.php?option=com_freevotes&task=domanda&nome=".$nome."&salva=".$salva."&id=".$result['id']."&valore1=".$result['nome']."'>".$result['nome']."</a>
			</td><td><a href='index.php?option=com_freevotes&cancella_domanda=".$result['id']."'><div style='width:32px; height:32px; background-image:url(\"templates/".$template_name."/images/toolbar/icon-32-cancel.png\");'></div></a></td></tr>";
			$i++;
		}
		echo"</table>";
	}
	if($_GET['view']=="risposte")
	{
		//creo i filtri
		echo"<form method='post'>
			<table><tr><td>".$cerca." ".$risposte."</td><td><input type='text' name='cerca_risposte' value='".$_POST['cerca_risposte']."'></td></tr>
			<tr><td>".$cerca." ".$domande."</td><td><input type='text' name='cerca_domande' value='".$_POST['cerca_domande']."'></td></tr>
			<tr><td><input type='submit' value='".$cerca."'></td><td></td></tr></table>
		</form>";
		
		//stampo le risposte
		if($_POST['cerca_risposte']=="" && $_POST['cerca_domande']=="")
			$query = "SELECT * FROM #__free_votes_risposte";
		else if($_POST['cerca_risposte']!="" && $_POST['cerca_domande']=="")
			$query = "SELECT * FROM #__free_votes_risposte WHERE nome='$_POST[cerca_risposte]'";
		else if($_POST['cerca_risposte']=="" && $_POST['cerca_domande']!="")
			$query = "SELECT * FROM #__free_votes_risposte AS R, #__free_votes_domande AS D WHERE D.id=R.domanda AND D.nome='$_POST[cerca_domande]'";
		else if($_POST['cerca_risposte']!="" && $_POST['cerca_domande']!="")
			$query = "SELECT * FROM #__free_votes_risposte AS R, #__free_votes_domande AS D WHERE D.id=R.domanda AND D.nome='$_POST[cerca_domande]' AND R.nome='$_POST[cerca_risposte]'";
		
		$database->setQuery($query);
		$results = $database->loadAssocList();

		$i=1;
		echo"<table class='adminlist'><thead><tr><td><strong>".$risposte."</strong></td><td><strong>".$domande."</strong></td><td><strong>".$colore."</strong></td><td><strong>".$cancella."</strong></td></tr></thead>";
		foreach($results as $result)
		{
			$query_d = "SELECT nome FROM #__free_votes_domande WHERE id=$result[domanda]";
			$database->setQuery($query_d);
			$results_d = $database->loadAssocList();
		
			if($i%2==0){$row="row0";}
			else{$row="row1";}
			echo "<tr class='".$row."'><td>
			
			<a class='modal' rel='{handler: \"iframe\", size: {x: 300, y: 200}, onClose: function() {}}' href='index.php?option=com_freevotes&task=risposta&nome=".$nome."&salva=".$salva."&colore=".$colore."&domande=".$domande."&genera=".$genera."&elenco_domande=".$elenco_dom."&id=".$result['id']."&valore1=".$result['nome']."&valore2=".substr($result['colore'],1,strlen($result['colore']))."&valore3=".$result['domanda']."'>".$result['nome']."
			
			</a></td><td>".$results_d[0]['nome']."</td><td>".$result['colore']."<div style='width:30px; height:30px; background:".$result['colore']."'></div></td><td><a href='index.php?option=com_freevotes&view=risposte&cancella_risposta=".$result['id']."'><div style='width:32px; height:32px; background-image:url(\"templates/".$template_name."/images/toolbar/icon-32-cancel.png\");'></div></a></td></tr>";
			$i++;
		}
		echo"</table>";
	}
}