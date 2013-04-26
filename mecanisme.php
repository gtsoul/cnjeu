
<?php 


    define( '_JEXEC', 1 );

    define('JPATH_BASE', dirname(__FILE__) );

    define( 'DS', DIRECTORY_SEPARATOR );

    require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );



		$db = JFactory::getDBO();
		$db->setQuery('select distinct mecanisme from cnj_jeu');


		$results = $db->loadObjectList(); 
		
		var_dump($results);
?>



