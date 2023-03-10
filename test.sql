-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 10 mars 2023 à 14:00
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `contributions`
--

DROP TABLE IF EXISTS `contributions`;
CREATE TABLE IF NOT EXISTS `contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_finance` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `contributions`
--

INSERT INTO `contributions` (`id`, `id_finance`, `id_user`) VALUES
(3, 5, 6),
(4, 6, 5),
(5, 7, 4),
(6, 8, 3),
(7, 9, 2),
(8, 10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `finances`
--

DROP TABLE IF EXISTS `finances`;
CREATE TABLE IF NOT EXISTS `finances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text NOT NULL,
  `amount` varchar(255) NOT NULL,
  `date_add` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `finances`
--

INSERT INTO `finances` (`id`, `id_page`, `type`, `description`, `amount`, `date_add`, `active`) VALUES
(3, 3, 1, 'Recettes totales (point 0)', '212169', '2022-04-09 20:51:25', 1),
(4, 3, 0, 'Dépenses totales (point 0)', '172105', '2022-04-09 20:52:02', 1),
(5, 3, 2, 'Apport de Anthony (Apport global)', '37359', '2022-04-09 20:52:20', 1),
(6, 3, 2, 'Apport de Julian (Apport global)', '44862', '2022-04-09 20:52:44', 1),
(7, 3, 2, 'Apport de Robin (Apport global)', '44862', '2022-04-09 20:53:04', 1),
(8, 3, 2, 'Apport de Kuro (Apport global)', '43862', '2022-04-09 20:53:37', 1),
(9, 3, 2, 'Apport de Jey (Apport global)', '7942', '2022-04-09 20:54:09', 1),
(10, 3, 2, 'Apport de Quentin (Apport global)', '5732', '2022-04-09 20:54:20', 1),
(11, 3, 0, 'Mise à jour caisse réelle', '1764', '2022-04-09 20:56:03', 1),
(12, 3, 0, 'Remboursement Robin', '14652', '2022-05-09 11:17:09', 1),
(13, 3, 1, '1 EP (Bandcamp)', '2260', '2022-05-30 08:47:13', 1);

-- --------------------------------------------------------

--
-- Structure de la table `inventories`
--

DROP TABLE IF EXISTS `inventories`;
CREATE TABLE IF NOT EXISTS `inventories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `picture` varchar(255) NOT NULL DEFAULT 'assets/img/pages/default.jpg',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `inventories`
--

INSERT INTO `inventories` (`id`, `id_page`, `description`, `price`, `stock`, `picture`, `active`) VALUES
(1, 3, 'T-Shirt Noir & Blanc', '1000', 0, 'assets/img/pages/1_0024604254_10.jpg', 1),
(2, 3, 'T-Shirt Couleur', '1500', 0, 'assets/img/pages/2_0024603456_10.jpg', 1),
(3, 3, 'EP (Version ConflikArts)', '1000', 100, 'assets/img/pages/3_b3e185da4d55253d08714b4a0a3b4d87.jpg', 1),
(4, 3, 'EP (Version Hurricane)', '1000', 99, 'assets/img/pages/4_b3e185da4d55253d08714b4a0a3b4d87.jpg', 1),
(5, 3, 'EP (Bandcamp)', '2260', 0, 'assets/img/pages/5_b3e185da4d55253d08714b4a0a3b4d87.jpg', 0);

-- --------------------------------------------------------

--
-- Structure de la table `invitations`
--

DROP TABLE IF EXISTS `invitations`;
CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `id_page` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `date_invitation` datetime NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `invitations`
--

INSERT INTO `invitations` (`id`, `mail`, `id_page`, `uuid`, `date_invitation`, `active`) VALUES
(1, 'jey.lyche@gmail.com', 3, '7o4WQw7731', '2022-03-06 18:12:05', 0),
(2, 'kuro.drumbass@gmail.com', 3, '5k5d36P062', '2022-03-06 18:30:43', 0),
(3, 'q.schifferle@coprotec.net', 3, 'ZWxf65v0AH', '2022-03-14 20:21:31', 0),
(4, 'eden.sight@gmail.com', 3, '8d6A6O9875', '2022-03-14 20:44:03', 0);

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`id`, `id_page`, `id_user`, `active`) VALUES
(1, 3, 1, 1),
(3, 5, 1, 1),
(4, 3, 2, 1),
(5, 3, 3, 1),
(6, 3, 4, 1),
(7, 3, 5, 1),
(8, 3, 6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_creator` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL DEFAULT 'assets/img/pages/default.jpg',
  `uuid` varchar(10) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `pages`
--

INSERT INTO `pages` (`id`, `id_creator`, `name`, `picture`, `uuid`, `active`) VALUES
(3, 1, 'Howl Of Tides', 'assets/img/pages/3_248356717_1053684152052949_593506537455465325_n.jpg', '8H73b2R8yT', 1),
(5, 1, 'Repeat Endless', 'assets/img/pages/5_pat linkedin.png', 'BdG4x05lT0', 1);

-- --------------------------------------------------------

--
-- Structure de la table `product_sizes`
--

DROP TABLE IF EXISTS `product_sizes`;
CREATE TABLE IF NOT EXISTS `product_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_inventory` int(11) NOT NULL,
  `id_size` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `id_inventory`, `id_size`, `stock`) VALUES
(1, 1, 3, 15),
(2, 1, 4, 20),
(3, 1, 5, 15),
(4, 1, 6, 13),
(5, 1, 7, 3),
(6, 2, 3, 14),
(7, 2, 4, 36),
(8, 2, 5, 26),
(9, 2, 6, 18),
(10, 2, 7, 6);

-- --------------------------------------------------------

--
-- Structure de la table `ranks`
--

DROP TABLE IF EXISTS `ranks`;
CREATE TABLE IF NOT EXISTS `ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `finances` int(11) NOT NULL DEFAULT '0',
  `inventory` int(11) NOT NULL DEFAULT '0',
  `settings` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ranks`
--

INSERT INTO `ranks` (`id`, `id_page`, `id_user`, `label`, `finances`, `inventory`, `settings`) VALUES
(1, 3, 1, 'Administrateur', 1, 1, 1),
(2, 5, 1, 'Administrateur', 1, 1, 1),
(3, 3, 2, 'Trésorier', 1, 1, 0),
(4, 3, 3, 'Membre', 0, 0, 0),
(5, 3, 4, 'Membre', 0, 0, 0),
(6, 3, 5, 'Membre', 0, 1, 0),
(7, 3, 6, 'Membre', 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `sizes`
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE IF NOT EXISTS `sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `sizes`
--

INSERT INTO `sizes` (`id`, `label`) VALUES
(1, 'XXS'),
(2, 'XS'),
(3, 'S'),
(4, 'M'),
(5, 'L'),
(6, 'XL'),
(7, 'XXL');

-- --------------------------------------------------------

--
-- Structure de la table `sold`
--

DROP TABLE IF EXISTS `sold`;
CREATE TABLE IF NOT EXISTS `sold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_finance` int(11) NOT NULL,
  `id_inventory` int(11) NOT NULL,
  `number_sold` int(11) NOT NULL,
  `is_garment` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `sold`
--

INSERT INTO `sold` (`id`, `id_finance`, `id_inventory`, `number_sold`, `is_garment`) VALUES
(1, 13, 5, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `date_registration` datetime NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `mail`, `password`, `profile_picture`, `date_registration`, `confirmed`, `active`) VALUES
(1, 'SCHIFFERLE', 'Quentin', 'quentin.schifferle@gmail.com', '$2y$10$5ZEFTiCp7aIPRvOgdTqw5ONkee8x4dYVJIY6VEqTxifxRZX7m//Ne', 'assets/img/gg7.jpg', '2022-03-03 10:45:46', 1, 1),
(2, 'LYCHE', 'Jey', 'jey.lyche@gmail.com', '$2y$10$e.wNFfSQow5XV6/FfV4UGOL.XRW4G7pKOgdVCaqN.jgAyl5nK7Q1e', 'assets/img/gg7.jpg', '2022-03-04 16:43:57', 1, 1),
(3, 'DRUMBASS', 'Kuro', 'kuro.drumbass@gmail.com', '$2y$10$MK.2WwOaXFp81HPnSW8FOeb6aiB0yN/pjGe8cizmbFh/XIdCqUj0q', 'assets/img/gg5.jpg', '2022-03-06 18:44:19', 1, 1),
(4, 'LEHE', 'Robin', 'q.schifferle@coprotec.net', '$2y$10$TSnDStwyV6v3Au/TkU8VD.wSnBGa47VL59NgtwUzyrDv5/g7rxiKe', 'assets/img/gg2.jpg', '2022-03-14 20:22:06', 1, 1),
(5, 'SIGHT', 'Julian', 'eden.sight@gmail.com', '$2y$10$.YvzxJT3ta3iwQ.TpqqiL.ZqL5xq5ZRmbMNAcNa8QTeWN3r.IfmiS', 'assets/img/gg7.jpg', '2022-03-14 20:44:28', 1, 1),
(6, 'TOUVENOT', 'Anthony', 'ludogh68@gmail.com', '$2y$10$s6E0gDQs.iL34jEmpvDKo.pSTv68q.hdb8ka0bmwwKmhxN9KgPcC6', 'assets/img/gg6.jpg', '2022-03-15 15:21:20', 1, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
