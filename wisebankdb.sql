-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 mai 2023 à 08:17
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET FOREIGN_KEY_CHECKS=0;
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
-- Structure de la table `actionlogs`
--

DROP TABLE IF EXISTS `actionlogs`;
CREATE TABLE IF NOT EXISTS `actionlogs` (
  `idaction` int NOT NULL AUTO_INCREMENT,
  `typaction` int NOT NULL,
  `actionuser` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dateaction` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idaction`),
  KEY `FK_credit_logsuser` (`actionuser`),
  KEY `typaction` (`typaction`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actionlogs`
--

INSERT INTO `actionlogs` (`idaction`, `typaction`, `actionuser`, `dateaction`) VALUES
(1, 1, '10000000000', '2023-04-23 02:47:46'),
(2, 1, '10000000000', '2023-04-23 02:47:46'),
(3, 1, '10000000000', '2023-04-24 14:28:35'),
(4, 1, '10000000000', '2023-04-25 15:31:02'),
(5, 1, '10000000000', '2023-04-25 15:36:37'),
(6, 1, '10000000000', '2023-04-25 15:57:23'),
(7, 3, '10000000000', '2023-05-03 14:34:09'),
(8, 2, '10000000000', '2023-05-03 21:49:16'),
(9, 2, '10000000000', '2023-05-03 21:49:20'),
(10, 2, '10000000000', '2023-05-03 21:49:45'),
(11, 1, '10000000002', '2023-05-04 08:57:19');

-- --------------------------------------------------------

--
-- Structure de la table `actionsindex`
--

DROP TABLE IF EXISTS `actionsindex`;
CREATE TABLE IF NOT EXISTS `actionsindex` (
  `idaction` int NOT NULL AUTO_INCREMENT,
  `libaction` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`idaction`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actionsindex`
--

INSERT INTO `actionsindex` (`idaction`, `libaction`) VALUES
(1, 'Nouveau crédit'),
(2, 'Création nouvel utilisateur'),
(3, 'Création nouveau compte'),
(4, 'Création nouvel administrateur'),
(5, 'Supprimer un compte'),
(6, 'Supprimer un utilisateur'),
(7, 'Supprimer un compte administrateur'),
(8, 'Supprimer un compte banquier'),
(9, 'Supprimer un compte conseiller'),
(10, 'Supprimer un crédit');

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `idmsg` int NOT NULL AUTO_INCREMENT,
  `envoyeurid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `destinataireid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `daterdv` datetime NOT NULL,
  `time` datetime NOT NULL,
  `requeststatus` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmsg`),
  KEY `FK_users_chat` (`envoyeurid`),
  KEY `FK_chat_conseiller` (`destinataireid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chat`
--

INSERT INTO `chat` (`idmsg`, `envoyeurid`, `destinataireid`, `chat`, `daterdv`, `time`, `requeststatus`) VALUES
(1, '10000000000', '10000000002', 'DONNE RDV !!', '2023-03-16 11:00:00', '2023-03-08 11:11:00', 2),
(2, '10000000000', '10000000002', 'ABOULE UN MAX DE MOULA', '2023-03-12 12:00:00', '2023-03-09 01:35:00', 1),
(3, '10000000000', '10000000002', 'Bonjour donne des sous', '2023-03-25 10:22:00', '2023-03-09 08:07:00', 1),
(4, '10000000000', '10000000002', 'ABOULE UN MAX DE MOULA', '2023-03-24 21:20:00', '2023-03-09 10:21:00', 1),
(5, '10000000000', '10000000002', 'Aled', '2023-04-08 12:12:00', '2023-03-16 11:23:00', 1),
(6, '10000000000', '10000000002', 'DONNE RDV !!', '2023-04-14 12:40:00', '2023-04-06 10:40:00', 3),
(7, '10000000000', '10000000002', 'ABOULE UN MAX DE MOULA', '2023-04-30 12:30:00', '2023-04-27 08:32:00', 1),
(8, '10000000000', '10000000002', 'Bonjour donne des sous', '2023-04-15 12:20:00', '2023-04-27 10:33:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

DROP TABLE IF EXISTS `comptes`;
CREATE TABLE IF NOT EXISTS `comptes` (
  `userid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `RIB` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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

INSERT INTO `comptes` (`userid`, `RIB`, `BIC`, `comptenom`, `solde`, `decouvert_autorise`) VALUES
('10000000000', 'FR76 69420 10000000000 25', 'WSBNFRXX ', 'Courant', 11084800, 10000),
('10000000000', 'FR76 69420 10000000000 87', 'WSBNFRXX ', 'Etudiant', 70, 500),
('10000000001', 'FR76 69420 10000000001 06', 'WSBNFRXX ', 'Courant', 121628, 400),
('10000000002', 'FR76 69420 10000000002 13', 'WSBNFRXX', 'Etudiant', 50020, 100),
('20639419637', 'FR76 69420 20639419637 65', 'WSBNFRXX ', 'Epargne', 115421, 0),
('77036964710', 'FR76 69420 77036964710 75', 'WSBNFRXX', 'Courant', 50779, 100);

-- --------------------------------------------------------

--
-- Structure de la table `credits`
--

DROP TABLE IF EXISTS `credits`;
CREATE TABLE IF NOT EXISTS `credits` (
  `creditid` int NOT NULL AUTO_INCREMENT,
  `compteid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `soldepret` float NOT NULL,
  `remboursement` float NOT NULL,
  `echeance` date NOT NULL,
  `date` date NOT NULL,
  `interet` float NOT NULL,
  `valeur_remboursment` float NOT NULL,
  `conseillerid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `typeprelevement` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `raison` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`creditid`),
  KEY `FK_compte_credit` (`compteid`),
  KEY `FK_user_conseiller` (`conseillerid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `credits`
--

INSERT INTO `credits` (`creditid`, `compteid`, `soldepret`, `remboursement`, `echeance`, `date`, `interet`, `valeur_remboursment`, `conseillerid`, `typeprelevement`, `raison`) VALUES
(1, 'FR76 69420 10000000000 25 ', 100, 0, '2023-04-25', '2023-04-23', 1, 0, '10000000000', 'Mensuel', 'Test'),
(2, 'FR76 69420 10000000000 25 ', 100, 0, '1000-02-10', '2022-12-01', 2.3, 0, '10000000000', 'Annuel', 'fasfsa'),
(3, 'FR76 69420 10000000000 25 ', 500, 0, '2023-04-20', '2024-04-23', 2.3, 0, '10000000000', 'Trimestriel', 'Test'),
(4, 'FR76 69420 77036964710 75 ', 50000, 0, '2001-09-11', '2025-04-23', 0.5, 0, '10000000000', 'Journalier', 'Cotton field investment - Pret etudiant'),
(5, 'FR76 69420 77036964710 75 ', 500, 0, '2001-09-10', '2025-04-23', 0.5, 0, '10000000000', 'Hebdomadaire', 'CIA requirements | Pret etudiant'),
(6, 'FR76 69420 10000000000 25 ', 50000, 500, '2505-00-00', '2023-04-29', 25, 0.5, '10000000000', 'Hebdomadaire', 'CIA requirements | Pret etudiant'),
(7, 'FR76 69420 10000000002 13 ', 50000, 5000, '2505-00-00', '2023-10-11', 4, 1.5, '10000000002', 'Hebdomadaire', 'Rachat auchan | Pret etudiant');

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
(1, 'Utilisateur'),
(2, 'Conseiller'),
(3, 'Banquier'),
(4, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
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
('41157767050', 'Hawk', 'Mike', '2023-04-14', '$2y$10$DR.cTUYdS3FFWdobAaEbKe/eZcd2jb.5XDj6.a0VPQaA783Vd9vSS', 'Mike.hawk@gmail.com', '+333532131', '20639419637\"', 1),
('42855391884', 'test', 'test', '2023-03-24', '$2y$10$FCPsCXK4f/lpdPpFPtxFI.J7rJFKhfFUNAGU2L3Xj7N8r8Fj325qK', 'test@mail', '+335114514', '0', 1),
('56844909537', 'Bull', 'Dreamy', '1992-02-18', '$2y$10$/Doitd/Y15cN8/IzvRW4rupSrDAhsGLCH.zQ/NhIbwWv8yn5iD/um', 'dreamybull@ambatakam.boutanat', '+3369696969', '20639419637\"', 1),
('71043740740', 'Doutaz', 'Mathys', '2004-05-15', '$2y$10$QhqtlhuX4z.O.rybQQqKh.JPZDKsTyqz4AA2m9ijt30ZjFm9crqjO', 'mathysdoutaz@wisemail.com', '0783775444', '', 3),
('77036964710', 'Cardeillac', 'Cyril', '2002-12-09', '$2y$10$fKJSrP/1tk7GkFJgKMK9wObfzuZcSwKm1puUV2Aic2pHbV3IWhP5i', 'cyril@wisemail.com', '+33 601470917', '20639419637\"', 1);

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
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `raison` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`idvirement`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `virements`
--

INSERT INTO `virements` (`idvirement`, `id_destinataire`, `id_envoyeur`, `valeur`, `date`, `raison`) VALUES
(1, 'FR76 69420 77036964710 75', 'FR76 69420 10000000000 25', 69, '2023-04-30 02:53:50', '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `actionlogs`
--
ALTER TABLE `actionlogs`
  ADD CONSTRAINT `FK_credit_actionindex` FOREIGN KEY (`typaction`) REFERENCES `actionsindex` (`idaction`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_credit_logsuser` FOREIGN KEY (`actionuser`) REFERENCES `credits` (`conseillerid`);

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
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_perms` FOREIGN KEY (`permissions`) REFERENCES `permissions` (`permissionid`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
