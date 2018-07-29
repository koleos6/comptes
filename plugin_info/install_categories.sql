CREATE TABLE IF NOT EXISTS `comptes_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT DEFAULT NULL,
  `level` int(11) NULL,
  `users` TEXT DEFAULT NULL,
  `position` int(11) NULL,
  `image` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comptes_categories` (`id`, `name`, `level`, `users`, `position`, `image`) VALUES
(0, 'Aucune', 0, 'admin', 1, '{"tagColor":"#c266c2","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-billets1''><\\/i>"}'),