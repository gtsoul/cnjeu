<?php
    define( '_JEXEC', 1 );

    define('JPATH_BASE', dirname(__FILE__) );

    define( 'DS', DIRECTORY_SEPARATOR );

    require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

    $search = JRequest::getVar('add_auteur');
    
    if($search) {
        $db	= JFactory::getDBO();
        $db->setQuery(
                'SELECT a.*' .
                ' FROM cnj_auteur AS a' .
                ' WHERE a.nom LIKE "%'.  mysql_real_escape_string($search) .'%" OR a.alias LIKE "%'.  mysql_real_escape_string($search) .'%"' .
                ' OR soundex(a.nom) = soundex("'.$search.'")  OR soundex(a.alias) = soundex("'.$search.'")' .
                ' ORDER BY a.nom asc'
        );
        
        $data = $db->loadObjectList();
        
        if(count($data) > 0) {
            echo "<ul>\n";
            foreach($data as $d) {
                echo "<li>" . $d->nom . ' - #' . $d->id_auteur . "</li>\n";
            }
            echo "</ul>";
        }
    }
    else echo "";
?>
