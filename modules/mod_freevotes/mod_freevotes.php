<?php
/*
* @name freevote 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$solo_registrati_error=JText::_('MOD_FREEVOTES_SOLO_REGISTRATI_ERROR');
$nuovo_voto_mex=JText::_('MOD_FREEVOTES_NUOVO_VOTO_MEX');
$hai_gia_votato_mex=JText::_('MOD_FREEVOTES_HAI_GIA_VOTATO_MEX');
$voti_aggiornati_mex=JText::_('MOD_FREEVOTES_VOTI_AGGIORNATI_MEX');
$errore_range=JText::_('MOD_FREEVOTES_ERRORE_RANGE');
$errore_campi_vuoti=JText::_('MOD_FREEVOTES_ERRORE_CAMPI_VUOTI');
$voti_txt=JText::_('MOD_FREEVOTES_VOTI_TXT');
$vota_txt=JText::_('MOD_FREEVOTES_VOTA_TXT');
$inserisci=JText::_('MOD_FREEVOTES_INSERISCI');
$inserisci_nuova=JText::_('MOD_FREEVOTES_INSERISCI_NUOVA');
$oggetto_mail=JText::_('MOD_FREEVOTES_OGGETTO_MAIL');
$corpo_mail=JText::_('MOD_FREEVOTES_CORPO_MAIL');

function paginaCorrente() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on")
	{
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
$Jroot=paginaCorrente();
$Jroot=explode("index.php",$Jroot);
$Jroot=$Jroot[0];
echo"<script>Jroot='".$Jroot."';</script>";

echo'<style type="text/css">
.altezza{
	height:28px;
}
.risposta{
	width:70px; 
	float:left;
	height:20px;
}
.percentuale{
	width:145px; 
	float:left;
}
.voto{
	float:left;
	padding:4px 0 0 7px;
	width:17px;
	height:20px;
	background-image:url("'.$Jroot.'modules/mod_freevotes/images/vuoto.png");
}
#voti{
	height:250px;
	width:350px;
	float:right;
}
.bottoni{
	cursor:pointer;
	width:145px;
	text-align:center;
	margin:15px auto 15px auto;
	
	background-image: linear-gradient(bottom, #3fc7d9 0%, #FFFFFF 63%);
	background-image: -o-linear-gradient(bottom, #3fc7d9 0%, #FFFFFF 63%);
	background-image: -moz-linear-gradient(bottom, #3fc7d9 0%, #FFFFFF 63%);
	background-image: -webkit-linear-gradient(bottom, #3fc7d9 0%, #FFFFFF 63%);
	background-image: -ms-linear-gradient(bottom, #3fc7d9 0%, #FFFFFF 63%);

	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #3fc7d9),
		color-stop(0.63, #FFFFFF)
	);
	
	border: 1px solid #000;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px; 
}
#vota_button, #inserisci_button{
	width:70px;
	margin:10px 0 0 0;
}
#ins_nuova_risp{
	width:100%;
	border-top:1px solid #000;
	padding:15px 0 0 0;
	margin:45px 0 0 0;
}
#titolo{
	font-weight:bold;
	text-align:center;
	font-size:20px;
	margin-bottom:35px;
}
</style>';

function jomail($destinatario, $oggetto, $corpo)
{
	$mailer =& JFactory::getMailer();
	
	//mittente
	$config =& JFactory::getConfig();
	$sender = array( 
	$config->getValue( 'config.mailfrom' ),
	$config->getValue( 'config.fromname' ) );

	$mailer->setSender($sender);
	
	//destinatario o array destinatari
	$recipient = $destinatario;
	$mailer->addRecipient($recipient);
	
	//messaggio e oggetto
	$mailer->setSubject($oggetto);
	$mailer->setBody($corpo);
	
	//invia mail
	$send =& $mailer->Send();
	/*if ( $send !== true ) {
		JError::raiseWarning( 100, 'Error sending email: ' . $send->message);
	} else {
		JFactory::getApplication()->enqueueMessage('Mail sent');
	}*/
}

//recupero i valori del modulo
$domanda 		= htmlentities($params->get('domanda', ''),ENT_QUOTES);
$range_da 		= $params->get('range_da', '');
$range_a		= $params->get('range_a', '');
$modifica_voti	= $params->get('modifica_voti', '');
$raggio		= $params->get('raggio', '');
$label		= $params->get('label', '');
$legend		= $params->get('legend', '');

//valori di default
if($range_da==""){$range_da=0;}
if($range_a==""){$range_a=5;}
if($modifica_voti==""){$modifica_voti=0;}
if($raggio==""){$raggio=55;}
if($label==""){$label=1;}
if($legend==""){$legend=0;}

//errori
if($range_da<0 || $range_a<0 || !is_numeric($range_da) || !is_numeric($range_a) || $range_a<=$range_da){
	echo"<script type='text/javascript'>alert('".$errore_range."');</script>";
}
if($domanda==""){echo"<script type='text/javascript'>alert('".$errore_campi_vuoti."');</script>";}
//fine recupero i valori del modulo

//inserisco nuova risposta
$database =& JFactory::getDBO();
if(isset($_POST['nuova_risposta']))
{
	$r=dechex(rand(0,255)); if(strlen($r)<2){$r.=0;}
	$g=dechex(rand(0,255)); if(strlen($g)<2){$g.=0;}
	$b=dechex(rand(0,255)); if(strlen($b)<2){$b.=0;}
	
	$query='INSERT INTO #__free_votes_risposte (nome,colore,domanda) VALUES ("'.htmlentities($_POST['nuova_risposta'],ENT_QUOTES).'","#'.$r.$g.$b.'",'.$domanda.')';
	$database->setQuery($query);
	$results=$database->query();
	
	//invio mail all'admin
	require_once("configuration.php");
	$config=new JConfig();

	jomail($config->mailfrom, str_replace("[site]",$config->sitename,$oggetto_mail), str_replace("[response]",htmlentities($_POST['nuova_risposta'],ENT_QUOTES),str_replace("[site]",$config->sitename,$corpo_mail)));
}
//fine inserisco nuova risposta

//inserisco voto nel db
$id_user =& JFactory::getUser()->get('id');
if(isset($_POST['valori_da_aggiungere']))
{
	if($id_user==0){
		JError::raiseWarning( 100, $solo_registrati_error);
	}
	else
	{
		$voti_gia_fatti=0; $voti_aggiornati=0; $nuovi_voti=0;
		$Dati=explode("<voto>",$_POST['valori_da_aggiungere']);
		foreach($Dati as $dato)
		{
			$valori=explode("<valori>",$dato);
			if($valori[0]==""){break;}
			
			$query1_t = "SELECT * FROM #__free_votes_risposte WHERE nome='".$valori[0]."'";
			$database->setQuery($query1_t);
			$results1_t = $database->loadAssocList();
			
			$query1 = "SELECT * FROM #__free_votes_risposte_user WHERE id_user=$id_user AND risposta='".$results1_t[0]['id']."'";
			$database->setQuery($query1);
			$results1 = $database->loadAssocList();
			
			foreach($results1 as $rr){$exists=$rr['id']; break;}
			
			if($exists!="")//se c'è già un voto per quell'utente su quella risposta
			{
				if($modifica_voti==0){
					$voti_gia_fatti++;
					continue;
				}
				
				$query='UPDATE #__free_votes_risposte_user SET voto='.mysql_real_escape_string($valori[1]).' WHERE id='.$exists;
				$database->setQuery($query);
				$results=$database->query();
				$voti_aggiornati++;
				
				continue;
			}

			$query='INSERT INTO #__free_votes_risposte_user (id_user,risposta,voto) VALUES ('.$id_user.','.$results1_t[0]['id'].','.mysql_real_escape_string($valori[1]).')';
			$database->setQuery($query);
			$results=$database->query();
			$nuovi_voti++;
		}
		if($voti_gia_fatti>0){JError::raiseNotice( 100, $hai_gia_votato_mex);}
		if($voti_aggiornati>0){JFactory::getApplication()->enqueueMessage($voti_aggiornati_mex);}
		else if($nuovi_voti>0){JFactory::getApplication()->enqueueMessage($nuovo_voto_mex);}
	}
}
//fine inserisco voto nel db

//estrapolo le percentuali dei voti
$query = "SELECT * FROM #__free_votes_risposte_user AS A, #__free_votes_risposte AS B WHERE B.domanda='$domanda' AND A.risposta=B.id ORDER BY B.nome";
$database->setQuery($query);
$results = $database->loadAssocList();

$precedente=""; $somma=Array();
foreach($results as $result)
{
	if($result['nome']!=$precedente)
	{
		$somma[]=Array($result['nome'],$result['voto'],$result['colore']);
		$precedente=$result['nome'];
	}
	else
		$somma[count($somma)-1][1]+=$result['voto'];
}

echo"<script type='text/javascript'>var browserData=[];";
for($i=0;$i<count($somma);$i++)
{	
	echo"
	browserData.push({
		name:'".$somma[$i][0]."',
		y:".$somma[$i][1].",
		color:'".$somma[$i][2]."'
	});";
}

echo"</script>";
//fine estrapolo le percentuali dei voti

//stampo a video
$query = "SELECT * FROM #__free_votes_domande WHERE id=$domanda";
$database->setQuery($query);
$results = $database->loadAssocList();

echo "<div id='titolo'>".$results[0]['nome']."</div>
<div id='voti'></div>";

$query = "SELECT * FROM #__free_votes_risposte WHERE domanda=$domanda";
$database->setQuery($query);
$results = $database->loadAssocList();
			
foreach($results as $campo)
{
	echo "
	<div class='altezza'>
		<div class='risposta'>".$campo['nome']."</div>
		<div class='percentuale'>";
			for($i=$range_da;$i<=$range_a;$i++)
				echo "<div class='voto' id='".$campo['nome']."_".$i."'>".$i."</div>";
	echo"</div></div>";
}
echo"<form method='post'>
	<input type='hidden' name='valori_da_aggiungere' id='valori_da_aggiungere' value=''>
	<input id='vota_button' class='bottoni' type='submit' value='".$vota_txt."'>
</form>

<form method='post' id='ins_nuova_risp'>
	".$inserisci_nuova." <input type='text' name='nuova_risposta'>
	<input id='inserisci_button' class='bottoni' type='submit' value='".$inserisci."'>
</form>

<br /><br />".base64_decode("PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZToxMHB4OyI+RnJlZSB2b3RlcyBwb2V3cmVkIGJ5IDxhIGhy
ZWY9Imh0dHA6Ly93d3cudGhlLWh0bWwtdG9vbC5jb20vIiB0YXJnZXQ9Il9ibGFuayI+VGhlIEh0
bWwgVG9vbDwvYT48L3NwYW4+");

//fine stampo a video

$cartella_file=dirname(__FILE__);
$cartella_file=explode("modules",$cartella_file);

$dir=dirname(__FILE__).'/insert.php';
$dir=explode("modules",$dir);

echo '<script type="text/javascript">var range_da="'.$range_da.'", range_a="'.$range_a.'", dir="'.$dir[1].'", id_user="'.$id_user.'", solo_registrati="'.$solo_registrati_error.'", voti_txt="'.$voti_txt.'", label='.$label.', legend='.$legend.', raggio='.$raggio.';</script>';
?>
<script>
   window.jQuery || document.write('<script src="http://code.jquery.com/jquery-latest.js"> \x3C/script>');
</script>
<?php echo '<script type="text/javascript" src="'.$Jroot.'modules'.$cartella_file[1].'/highcharts.js"></script>'; ?>
<script type="text/javascript">
	//creazione del grafico
		
	if(label==1){label=true;}else{label=false;}
	if(legend==1){legend=true;}else{legend=false;}
	
	var chart;
	( function($) {//riga per evitare il conflitto della variabile $
	
	$(document).ready(function() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'voti',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: ''
			},
			tooltip: {
				formatter: function() {
					return '<b>'+ this.point.name +'</b>: '+ Math.floor(this.percentage) +' %<br />'+this.y+": "+voti_txt;
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: legend,
					dataLabels: {
						enabled: label,
						color: '#000000',
						connectorColor: '#000000',
						formatter: function() {
							return '<b>'+ this.point.name +'</b>: '+this.y+": "+voti_txt;
						}
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Browser share',
				size: raggio+'%',
				data: browserData
			}]
		});
	});
	
	var elenco_voti=new Array();
	$(".voto").click(function(){
		if(id_user==0){
			alert(solo_registrati);
		}
		else{
			var id=$(this).attr("id");
			id=id.split("_");
			
			var enter=0;
			for(var i=0;i<elenco_voti.length;i++)//controllo se c'è già un voto a questa domanda
			{
				if(elenco_voti[i][0]==id[0])//se c'è aggiorno il voto
				{
					elenco_voti[i][1]=id[1];
					enter=1;
					break;
				}
			}
			
			if(enter==0)//se è un voto nuovo lo aggiungo
			{
				elenco_voti[elenco_voti.length]=new Array(id[0],id[1]);
			}
			
			var nascosto="";
			for(var i=0;i<elenco_voti.length;i++)//inserisco l'elenco dei voti nel campo hidden del form
			{
				nascosto=nascosto+elenco_voti[i][0]+"<valori>"+elenco_voti[i][1]+"<voto>";
			}
			
			$("#valori_da_aggiungere").val(nascosto);
			
			for(var i=range_da;i<=range_a;i++)//mette le stelline piene
			{
				if(i<=id[1]){$("#"+id[0]+"_"+i).css("background-image","url('"+Jroot+"modules/mod_freevotes/images/pieno.png')");}
				else{$("#"+id[0]+"_"+i).css("background-image","url('"+Jroot+"modules/mod_freevotes/images/vuoto.png')");}
			}
		}
	});	
	
	} ) ( jQuery );//riga per evitare il conflitto della variabile $
</script>