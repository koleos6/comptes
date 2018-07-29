

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

INSERT INTO `comptes_categories` (`id`, `name`, `level`, `users`, `position`, `image`) VALUES
(1, 'Alimentation', 0, 'admin', 2, '{"tagColor":"#32b04c","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-panier2''><\\/i>"}'),
(2, 'Loisirs', 0, 'admin', 6, '{"tagColor":"#f04646","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-smiley-happy''><\\/i>"}'),
(3, 'Informatique', 1, 'admin', 14, '{"tagColor":"#ffcf3e","tagTextColor":"#400040","icon":"<i class=''icon plugin-comptes-ecran-et-tablette2''><\\/i>"}'),
(4, 'Culture', 1, 'admin', 9, '{"tagColor":"#f04646","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-livre17''><\\/i>"}'),
(32, 'Cadeaux', 1, NULL, 7, '{"tagColor":"#f04646","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-cadeau''><\\/i>"}'),
(33, 'Technologie', 0, NULL, 11, '{"tagColor":"#ffcf3e","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-doc-science''><\\/i>"}'),
(40, 'Sports', 1, NULL, 8, '{"tagColor":"#f04646","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-skateur''><\\/i>"}'),
(41, 'Domotique', 1, NULL, 13, '{"tagColor":"#ffcf3e","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-domotique''><\\/i>"}'),
(42, 'Robotique', 1, NULL, 15, '{"tagColor":"#ffcf3e","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-composant1''><\\/i>"}'),
(43, 'Téléphone portable', 1, NULL, 12, '{"tagColor":"#ffcf3e","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-phone1''><\\/i>"}'),
(44, 'Internet', 1, NULL, 16, '{"tagColor":"#ffcf3e","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-antenne4''><\\/i>"}'),
(45, 'Habillement', 0, NULL, 5, '{"tagColor":"#4558dc","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-vetements1''><\\/i>"}'),
(46, 'Quotidien divers', 0, NULL, 4, '{"tagColor":"#008000","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro2''><\\/i>"}'),
(47, 'Santé', 0, NULL, 3, '{"tagColor":"#009263","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-signe-vitaux3''><\\/i>"}'),
(48, 'Transports', 0, NULL, 17, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon transport-school31''><\\/i>"}'),
(49, 'Automobile', 1, NULL, 18, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon transport-car95''><\\/i>"}'),
(50, 'Train', 1, NULL, 25, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-train''><\\/i>"}'),
(51, 'Assurance', 2, NULL, 19, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-voiture2''><\\/i>"}'),
(52, 'Carburant', 2, NULL, 20, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon divers-fuel4''><\\/i>"}'),
(53, 'Péages - Parkings', 2, NULL, 21, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-sortie-billet''><\\/i>"}'),
(54, 'Achat - Crédit', 2, NULL, 22, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(55, 'Garage - box', 2, NULL, 24, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon plugin-comptes-voiture2''><\\/i>"}'),
(56, 'Entretien', 2, NULL, 23, '{"tagColor":"#c0c0c0","tagTextColor":"#000000","icon":"<i class=''icon maison-vacuum6''><\\/i>"}'),
(57, 'Energie', 0, NULL, 26, '{"tagColor":"#bb8151","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-eclair1''><\\/i>"}'),
(58, 'Electricité', 1, NULL, 27, '{"tagColor":"#bb8151","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-eclair1''><\\/i>"}'),
(59, 'Gaz', 1, NULL, 28, '{"tagColor":"#bb8151","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-eclair1''><\\/i>"}'),
(60, 'Bois', 1, NULL, 29, '{"tagColor":"#bb8151","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-eclair1''><\\/i>"}'),
(61, 'Finances', 0, NULL, 30, '{"tagColor":"#ff793e","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(62, 'Habitation', 0, NULL, 36, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon maison-house109''><\\/i>"}'),
(63, 'Impôts', 0, NULL, 43, '{"tagColor":"#fe80d6","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(64, 'Salaire - primes', 1, NULL, 31, '{"tagColor":"#ff793e","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(65, 'Banque', 1, NULL, 35, '{"tagColor":"#ff793e","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(66, 'Notes de frais', 1, NULL, 32, '{"tagColor":"#ff793e","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(67, 'Intérêts', 1, NULL, 34, '{"tagColor":"#ff793e","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(68, 'Don', 1, NULL, 33, '{"tagColor":"#ff793e","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(69, 'Achat - crédit', 1, NULL, 37, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(70, 'Charges', 1, NULL, 38, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(71, 'Assurance', 1, NULL, 39, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(72, 'Entretien - travaux', 1, NULL, 40, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-chantier3''><\\/i>"}'),
(73, 'Equipement', 1, NULL, 41, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon maison-king11''><\\/i>"}'),
(74, 'Jardin', 1, NULL, 42, '{"tagColor":"#3ea2ff","tagTextColor":"#ffffff","icon":"<i class=''icon nature-plant30''><\\/i>"}'),
(75, 'Vacances', 0, NULL, 10, '{"tagColor":"#c266c2","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-oasis1''><\\/i>"}'),
(76, 'Revenu', 1, NULL, 44, '{"tagColor":"#fe80d6","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(77, 'Foncier', 1, NULL, 45, '{"tagColor":"#fe80d6","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}'),
(78, 'Habitation', 1, NULL, 46, '{"tagColor":"#fe80d6","tagTextColor":"#ffffff","icon":"<i class=''icon plugin-comptes-euro1''><\\/i>"}');

