-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 02 mars 2023 à 07:24
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wisebankdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

DROP TABLE IF EXISTS `comptes`;
CREATE TABLE IF NOT EXISTS `comptes` (
  `userid` int NOT NULL,
  `comptenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `solde` float NOT NULL DEFAULT '20',
  `RIB` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `decouvert_autorise` int DEFAULT NULL,
  PRIMARY KEY (`RIB`),
  KEY `FK_users_comptes` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comptes`
--

INSERT INTO `comptes` (`userid`, `comptenom`, `solde`, `RIB`, `decouvert_autorise`) VALUES
(1, 'Epargne', 20, '69420413287504944655759', 200),
(1, 'Courant', 10000000, '69420631604116986272781', 10000),
(3, 'Courant', 1600, '69420712372832776950762', 500),
(2, 'Courant', 20, '69420741173351514093248', 1000);

-- --------------------------------------------------------

--
-- Structure de la table `conseillers`
--

DROP TABLE IF EXISTS `conseillers`;
CREATE TABLE IF NOT EXISTS `conseillers` (
  `idconseiller` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` int NOT NULL,
  PRIMARY KEY (`idconseiller`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `permissionid` int NOT NULL AUTO_INCREMENT,
  `permissionnom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`permissionid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`permissionid`, `permissionnom`) VALUES
(1, 'User'),
(2, 'Banquier'),
(3, 'Conseiller'),
(4, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `idconseiller` int NOT NULL,
  `permissions` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_users_perms` (`permissions`)
) ENGINE=InnoDB AUTO_INCREMENT=10000000007 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `userid`, `nom`, `prenom`, `date_naissance`, `password`, `mail`, `tel`, `idconseiller`, `permissions`) VALUES
(1, '10000000000', 'Zonca', 'Leo', '2003-10-11', '123456', 'leo.zonca@wisemail.com', '0646347071', 0, 4),
(2, '10000000001', 'Vernus', 'Paul', '2002-04-16', '123456', 'paulvernus@wisemail.com', '0782586136', 0, 1),
(3, '10000000002', 'Mocanu', 'Miruna', '2003-04-03', '123456', 'mirunamocanu@wisemail.com', '0617780388', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `virements`
--

DROP TABLE IF EXISTS `virements`;
CREATE TABLE IF NOT EXISTS `virements` (
  `idvirement` int NOT NULL AUTO_INCREMENT,
  `id_destinataire` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_envoyeur` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `valeur` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`idvirement`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `virements`
--

INSERT INTO `virements` (`idvirement`, `id_destinataire`, `id_envoyeur`, `valeur`, `date`) VALUES
(29, 'FR13 1273 9000 7064 3341 7217 M62', '69420712372832776950762', 121, '2001-03-23'),
(30, 'FR13 1273 9000 7064 3341 7217 M62', '69420712372832776950762', 45, '2001-03-23');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comptes`
--
ALTER TABLE `comptes`
  ADD CONSTRAINT `FK_users_comptes` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_perms` FOREIGN KEY (`permissions`) REFERENCES `permissions` (`permissionid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
