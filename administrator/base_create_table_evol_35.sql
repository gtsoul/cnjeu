
/*CREATE TABLE `cnj_jeu_to_motcle` (
  `id_jeu` int(11) NOT NULL,
  `id_motcle` int(11) NOT NULL,
  PRIMARY KEY (`id_jeu`,`id_motcle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/


/*CREATE TABLE `cnj_jeu_to_mecanisme` (
  `id_jeu` int(11) NOT NULL,
  `id_mecanisme` int(11) NOT NULL,
  PRIMARY KEY (`id_jeu`,`id_mecanisme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

*/

/*
drop table `cnj_motcle`;

CREATE TABLE `cnj_motcle` (
  `id_motcle` int(11) NOT NULL AUTO_INCREMENT,
  `motcle` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id_motcle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

*/



/*drop table `cnj_mecanisme`;

CREATE TABLE `cnj_mecanisme` (
  `id_mecanisme` int(11) NOT NULL AUTO_INCREMENT,
  `mecanisme` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id_mecanisme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
*/

select distinct transfert_mot_cle from cnj_jeu\G;


