<?php
    define( '_JEXEC', 1 );

    define('JPATH_BASE', dirname(__FILE__) );

    define( 'DS', DIRECTORY_SEPARATOR );

    require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

    $search = JRequest::getVar('add_mecanisme');
    
    if($search) {
        $db	= JFactory::getDBO();
        $db->setQuery(
                'SELECT d.*' .
                ' FROM cnj_mecanisme AS d' .
                ' WHERE d.mecanisme LIKE "%'.  mysql_real_escape_string($search) .'%"' .
                ' OR soundex(d.mecanisme) =  soundex("'.$search.'")'.
                ' ORDER BY d.mecanisme asc'
        );
        
        $data = $db->loadObjectList();
        
        if(count($data) > 0) {
            echo "<ul>\n";
            foreach($data as $d) {
                echo "<li>" . $d->mecanisme . ' - #' . $d->id_mecanisme . "</li>\n";
            }
            echo "</ul>";
        }
    }
    else echo "";
?>
