<?php

define( '_JEXEC', 1 );

define('JPATH_BASE', dirname(__FILE__) );

define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$sql = "SELECT path_hd,path_miniature,path_optimise FROM cnj_document WHERE id = ".htmlentities($_GET['id']);

$db	= JFactory::getDBO();
$db->setQuery($sql);
$data = $db->loadObjectList();

function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
function displayDocument($object, $path) {
  if($object->$path != '') {
    if(endsWith($object->$path, '.pdf') || endsWith($object->$path, '.PDF')) {
      echo '<td><a href="'.$object->$path.'">'.$object->$path.'</a></td>';
    } else {
      echo '<td><a href="'.$object->$path.'"><img src="'.$object->$path.'" style="max-width: 200px;"></a></td>';
    }
  } else {
    echo('<td></td>');
  }
}

echo '<input type="button" value="fermer" onclick="self.close()"><br/>';	
echo '<table>';
echo '<tr>';
// on fait une boucle qui va faire un tour pour chaque enregistrement

    echo '<td>miniature : </td>';
    echo '<td>vignette : </td>';
    echo '<td>haute d&eacute;finition : </td>';

for ($i = 0; $i < count($data); $i++) {
    $d = $data[$i];
    echo "</tr><tr>";
    displayDocument($d, 'path_miniature');    
    displayDocument($d, 'path_optimise');    
    displayDocument($d, 'path_hd');
}   
echo '</tr>';
echo '</table>';

echo '<input type="button" value="fermer" onclick="self.close()">';	

?>
