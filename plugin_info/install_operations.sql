CREATE TABLE IF NOT EXISTS `comptes_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `CatId` int(11) NOT NULL,
  `BankOperation` TEXT DEFAULT NULL,
  `Type` int(11) NOT NULL,
  `Amount` varchar(127),
  `OperationDate` date DEFAULT NULL,
  `CheckedOn` date DEFAULT NULL,
  `Checked` int(1) DEFAULT NULL,
  `hide` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
