-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 09 mars 2023 à 00:45
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

-- Structure de la table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `envoyeurid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `destinataireid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `daterdv` datetime NOT NULL,
  `time` datetime NOT NULL,
  `requeststatus` tinyint NOT NULL DEFAULT '0',
  KEY `FK_users_chat` (`envoyeurid`),
  KEY `FK_chat_conseiller` (`destinataireid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chat`
--

INSERT INTO `chat` (`envoyeurid`, `destinataireid`, `chat`, `daterdv`, `time`, `requeststatus`) VALUES
('10000000000', '10000000002', 'DONNE RDV !!', '2023-03-16 11:00:00', '2023-03-08 11:11:00', 0),
('10000000000', '10000000002', 'ABOULE UN MAX DE MOULA', '2023-03-12 12:00:00', '2023-03-09 01:35:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

CREATE TABLE IF NOT EXISTS `comptes` (
  `RIB` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `BIC` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'WSBNFRXX',
  `comptenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `solde` float NOT NULL DEFAULT '20',
  `decouvert_autorise` int DEFAULT NULL,
  PRIMARY KEY (`RIB`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comptes`
--

INSERT INTO `comptes` (`RIB`, `userid`, `BIC`, `comptenom`, `solde`, `decouvert_autorise`) VALUES
('FR76 69420 10000000000 25', '10000000000', 'WSBNFRXX ', 'Courant', 9848660, 10000),
('FR76 69420 10000000000 87', '10000000000', 'WSBNFRXX ', 'Etudiant', 20, 500),
('FR76 69420 10000000001 06', '10000000001', 'WSBNFRXX ', 'Courant', 18, 400),
('FR76 69420 20639419637 65', '20639419637', 'WSBNFRXX ', 'Epargne', 5020, 0);

-- --------------------------------------------------------

--
-- Structure de la table `credits`
--

CREATE TABLE IF NOT EXISTS `credits` (
  `creditid` int NOT NULL AUTO_INCREMENT,
  `compteid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `soldepret` float NOT NULL,
  `echeance` date NOT NULL,
  `interet` float NOT NULL,
  `conseillerid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`creditid`),
  KEY `FK_compte_credit` (`compteid`),
  KEY `FK_user_conseiller` (`conseillerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `permissionid` int NOT NULL AUTO_INCREMENT,
  `permissionnom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`permissionid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`permissionid`, `permissionnom`) VALUES
(1, 'Utilisateur'),
(2, 'Conseiller'),
(3, 'Banquier'),
(4, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `idconseiller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `permissions` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  KEY `FK_users_perms` (`permissions`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`userid`, `nom`, `prenom`, `date_naissance`, `password`, `mail`, `tel`, `idconseiller`, `permissions`) VALUES
('10000000000', 'Zonca', 'Leo', '2003-10-11', '$2y$10$OKBuAvYAGlyQA8YMNENv4O78Ge8yUwUnINpFfPCMfQ4LgpeuFW1Ra', 'leo.zonca@wisemail.com', '0646347071', '10000000002', 4),
('10000000001', 'Vernus', 'Paul', '2002-04-16', '$2y$10$FCPsCXK4f/lpdPpFPtxFI.J7rJFKhfFUNAGU2L3Xj7N8r8Fj325qK', 'paulvernus@wisemail.com', '0782586136', '20639419637', 1),
('10000000002', 'Mocanu', 'Miruna', '2003-04-03', '$2y$10$FCPsCXK4f/lpdPpFPtxFI.J7rJFKhfFUNAGU2L3Xj7N8r8Fj325qK', 'mirunamocanu@wisemail.com', '0617780388', '0', 3),
('20639419637', 'Lavaux', 'Bastien', '2004-07-21', '$2y$10$EKimhRi0Otuo0jy5H2Edd.p/4GSraDqrTXbOF6l2zS2uKJXODZlaO', 'bastien.lavaux@wisemail.com', '0750076268', '10000000002', 2),
('32407958244', 'tets', 'Tst_8', '2023-03-24', '$2y$10$ry5TxHGXQeU.Hny.hESYIOx96W2qgZAceuE8.Ym7yh5b5rrWahudi', 'WiseTree@tree', '45454', '0', 1),
('39762984437', 'Testy', 'Test', '2023-03-15', '$2y$10$ujNHv9jzN1poUcm6n.fWWuvaSDM8.Aeyrua4C7Ueew6ud2hwf2RVa', 'tesy@tes', '+333532131', '20639419637', 1),
('42855391884', 'test', 'test', '2023-03-24', '$2y$10$FCPsCXK4f/lpdPpFPtxFI.J7rJFKhfFUNAGU2L3Xj7N8r8Fj325qK', 'test@mail', '+335114514', '0', 1);

-- --------------------------------------------------------

--
-- Structure de la table `virements`
--

CREATE TABLE IF NOT EXISTS `virements` (
  `idvirement` int NOT NULL AUTO_INCREMENT,
  `id_destinataire` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_envoyeur` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `valeur` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`idvirement`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `virements`
--

INSERT INTO `virements` (`idvirement`, `id_destinataire`, `id_envoyeur`, `valeur`, `date`) VALUES
(31, 'FR76 69420 1000000000 25', 'FR76 69420 1000000000 25', 15, '2003-03-23'),
(32, 'FR76 69420 1000000000 25', 'FR76 69420 1000000000 25', 15, '2003-03-23'),
(33, 'FR76 69420 1000000000 25', 'FR76 69420 1000000000 25', 15, '2003-03-23'),
(34, 'FR76 69420 1000000001 06', 'FR76 69420 1000000000 25', 52, '2003-03-23'),
(35, 'FR76 69420 1000000001 06', 'FR76 69420 1000000000 25', 50, '2003-03-23'),
(36, 'FR76 69420 20639419637 65', 'FR76 69420 10000000000 25', 5000, '2003-03-23'),
(37, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 500, '2006-03-23'),
(38, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 45, '2006-03-23'),
(39, 'FR76 69420 1000000000 25', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(40, 'FR76 69420 1000000000 25', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(41, 'FR76 69420 1000000000 25', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(42, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 121, '2008-03-23'),
(43, 'FR76 69420 1000000000 25', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(44, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(45, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(46, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(47, 'FR76 69420 1000000001 06', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(48, 'FR13 1273 9000 7064 3341 7217 M62', 'FR76 69420 10000000000 25', 121, '2008-03-23'),
(49, 'FR13 1273 9000 7064 3341 7217 M69', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(50, 'FR13 1273 9000 7064 3341 7217 M69', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(51, 'FR13 1273 9000 7064 3341 7217 M69', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(52, 'FR13 1273 9000 7064 3341 7217 M69', 'FR76 69420 10000000000 25', 12121, '2008-03-23'),
(53, 'FR13 1273 9000 7064 3341 7217 M69', 'FR76 69420 10000000000 25', 12121, '2008-03-23');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `FK_chat_conseiller` FOREIGN KEY (`destinataireid`) REFERENCES `users` (`userid`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_users_chat` FOREIGN KEY (`envoyeurid`) REFERENCES `users` (`userid`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `comptes`
--
ALTER TABLE `comptes`
  ADD CONSTRAINT `FK_users_comptes` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Contraintes pour la table `credits`
--
ALTER TABLE `credits`
  ADD CONSTRAINT `FK_compte_credit` FOREIGN KEY (`compteid`) REFERENCES `comptes` (`RIB`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_user_conseiller` FOREIGN KEY (`conseillerid`) REFERENCES `users` (`userid`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_perms` FOREIGN KEY (`permissions`) REFERENCES `permissions` (`permissionid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
