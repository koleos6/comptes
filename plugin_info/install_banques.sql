CREATE TABLE IF NOT EXISTS `comptes_banques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT DEFAULT NULL,
  `logo_name` TEXT DEFAULT NULL,
  `logo_mini_name` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comptes_banques` (`id`, `name`, `logo_name`, `logo_mini_name`) VALUES
(1, 'Société Générale', 'societe_generale.jpg', 'societe_generale_mini.png'),
(2, 'La Banque Postale', 'banque_postale.jpg', 'banque_postale_mini.png'),
(3, 'LCL', 'LCL.jpg', 'LCL_mini.png'),
(4, 'Crédit Agricole', 'credit_agricole.png', 'credit_agricole_mini.png'),
(5, 'BNP Paribas', 'bnp_paribas.png', 'bnp_paribas_mini.png'),
(6, 'Crédit Mutuel', 'credit_mutuel.png', 'credit_mutuel_mini.png'),
(7, 'HSBC', 'hsbc.png', 'hsbc_mini.png'),
(8, 'Boursorama Banque', 'boursorama.png', 'boursorama_mini.png'),
(9, 'ING Direct', 'ing_direct.png', 'ing_direct_mini.png'),
(10, 'Groupama', 'groupama.png', 'groupama_mini.png'),
(11, 'CIC', 'cic.png', 'cic_mini.png'),
(12, 'Banque Populaire', 'banque_populaire.png', 'banque_populaire_mini.png'),
(13, 'Caisse d''épargne', 'caisse_epargne.png', 'caisse_epargne_mini.png'),
(14, 'Hello Bank!', 'hello_bank.png', 'hello_bank_mini.png'),
(15, 'Monabanq', 'monabanq.png', 'monabanq_mini.png'),
(16, 'Axa banque', 'axa.png', 'axa_mini.png'),
(17, 'Fortuneo banque', 'fortuneo.png', 'fortuneo_mini.png');
(18, 'Compte Nickel', 'compte_nickel.jpg', 'compte_nickel_mini.png');