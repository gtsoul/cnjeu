<?php

exec('mysqldump -hmysql51-41.pro -ucnjeucnjjeux -ppsz5qAdfs cnjeucnjjeux > backup_db');
exec('tar -zcvf backup_db.tar.gz backup_db');

exec('tar -zcvf backup_site.tar.gz _.htaccess .project logs language includes cli web.config.txt index.php htaccess.txt README.txt LICENSE.txt .htaccess configuration.php observatoire.php tremplin.php ludotheque.php conservatoire.php media modules libraries autocompletion-get-references.php autocompletion-get-auteurs.php autocompletion-get-distinctions.php autocompletion-get-documents.php templates cache components plugins ok.html voirDocument.php administrator backup.php');
?>
