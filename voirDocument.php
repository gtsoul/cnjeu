<?
$link = mysql_connect("mysql51-41.pro","cnjeucnjjeux","psz5qAdfs") or die("Impossible de se connecter : " . mysql_error());

mysql_select_db('cnjeucnjjeux');

// on crée la requête SQL
$sql = "SELECT path_hd,path_miniature,path_optimise FROM cnj_document WHERE id = ".$_GET['id'];

// on envoie la requête
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());

$data = mysql_fetch_assoc($req);
echo '<input type="button" value="fermer" onclick="self.close()"><br/>';	
echo '<table>';
echo '<tr>';
// on fait une boucle qui va faire un tour pour chaque enregistrement

    echo '<td>miniature : </td>';
    echo '<td>vignette : </td>';
    echo '<td>haute d&eacute;finition : </td>';
 
echo "</tr><tr>";
    echo '<td><a href="'.$data['path_miniature'].'""><img src="'.$data['path_miniature'].'" style="max-width: 200px;"></a></td>';
    echo '<td><a href="'.$data['path_optimise'].'""><img src="'.$data['path_optimise'].'" style="max-width: 250px;" ></a></td>';
    echo '<td><a href="'.$data['path_hd'].'""><img src="'.$data['path_hd'].'" style="max-width: 300px;"></a></td>';
echo '</tr>';
echo '</table>';

echo '<input type="button" value="fermer" onclick="self.close()">';	

mysql_close($link); 

?>
