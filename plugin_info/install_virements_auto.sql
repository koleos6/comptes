CREATE TABLE IF NOT EXISTS `comptes_virements_auto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NULL,
  `CatId` int(11) NULL,
  `Title` TEXT DEFAULT NULL,
  `Reference` TEXT DEFAULT NULL,
  `Amount` varchar(127),
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `frequence` int(11) NULL,
  `jour` int(11) NULL,
  `position` int(11) NULL,
  `comments` TEXT DEFAULT NULL,
  `compteur_frequence` int(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
