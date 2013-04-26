<?php




function approx($rech)
  {
  for ($i = 0; $i < strlen($rech); $i++) 
    { 
    $tableau[]=$rech[$i];
    } 
  return implode("%", $tableau);
}


	 


    define( '_JEXEC', 1 );

    define('JPATH_BASE', dirname(__FILE__) );

    define( 'DS', DIRECTORY_SEPARATOR );

    require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

    $search = JRequest::getVar('add_reference');

	
    
    if($search) {



        $db	= JFactory::getDBO();
        $db->setQuery(
                'SELECT r.*' .
                ' FROM cnj_reference AS r' .
                ' WHERE r.nom LIKE "%'.  mysql_real_escape_string($search) .'%" OR r.alias LIKE "%'.  mysql_real_escape_string($search) .'%"' .
                ' OR soundex(r.nom) = soundex("'.$search.'")  OR soundex(r.alias) = soundex("'.$search.'")' .
                ' ORDER BY r.nom asc'
        );
        
        $data = $db->loadObjectList();
        
        if(count($data) > 0) {
            echo "<ul>\n";
            foreach($data as $d) {
                echo "<li>" . $d->nom . ' - #' . $d->id_reference . "</li>\n";
            }
            echo "</ul>";
        
	}
	else
	{
       		$db	= JFactory::getDBO();
        	
		$query =  	'SELECT r.*' .
                	' FROM cnj_reference AS r' .
                	' WHERE soundex(r.nom) = soundex("'.$search.'") OR soundex(r.alias) = soundex("'.$search.'")' .
                	' ORDER BY r.nom asc';

		$db->setQuery($query);

       		$data = $db->loadObjectList();

        	if(count($data) > 0) {
            		echo "<ul>\n";
            		foreach($data as $d) {
                		echo "<li>" . $d->nom . ' - #' . $d->id_reference . "</li>\n";
            		}
            	echo "</ul>";
		}
	
	}
 	}


?>
