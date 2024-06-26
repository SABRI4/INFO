-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 24 mai 2024 à 17:57
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `depenses`
--

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

DROP TABLE IF EXISTS `depenses`;
CREATE TABLE IF NOT EXISTS `depenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `categorie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `depenses`
--

INSERT INTO `depenses` (`id`, `user_id`, `categorie`, `montant`, `date`, `description`) VALUES
(2, 1, 'alimentation', 8.00, '2024-05-06', 'mdco'),
(4, 2, 'alimentation', 20.00, '0000-00-00', 'Mcdo'),
(5, 1, 'alimentation', 150.00, '2024-05-08', ''),
(6, 1, 'alimentation', 15.00, '2024-05-03', 'mdco'),
(22, 4, 'alimentation', 90.00, '2024-05-16', 'courses'),
(23, 4, 'santé', 25.00, '2024-05-01', 'medecin'),
(24, 4, 'epargne', 200.00, '2024-05-03', ''),
(46, 8, 'transport', 200.00, '2024-05-16', ''),
(45, 8, 'alimentation', 25.00, '2024-05-16', ''),
(43, 8, 'alimentation', 100.00, '2024-05-22', 'table'),
(47, 8, 'alimentation', 12.00, '2024-04-05', ''),
(41, 6, 'alimentation', 1400.00, '2024-05-18', ''),
(40, 6, 'alimentation', 7.00, '2024-05-22', 'GREC '),
(39, 5, 'alimentation', 25.00, '2024-05-23', ''),
(38, 5, 'alimentation', 25.00, '2024-05-23', ''),
(36, 5, 'alimentation', 25.00, '2024-05-30', ''),
(35, 5, 'alimentation', 2129.00, '2024-05-29', ''),
(37, 5, 'alimentation', 30.00, '2024-05-16', ''),
(48, 8, 'alimentation', 125.00, '2024-03-23', ''),
(49, 8, 'alimentation', 256.00, '2024-05-25', ''),
(50, 11, 'loisirs', 34.00, '2024-05-24', '');

-- --------------------------------------------------------

--
-- Structure de la table `message_replies`
--

DROP TABLE IF EXISTS `message_replies`;
CREATE TABLE IF NOT EXISTS `message_replies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `user_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `posted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `public_messages`
--

DROP TABLE IF EXISTS `public_messages`;
CREATE TABLE IF NOT EXISTS `public_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `posted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ACTIVE` tinyint(1) DEFAULT NULL,
  `VIP` tinyint(1) DEFAULT NULL,
  `budget` int NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `couleur` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '#0c1b8c',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `photo`, `ACTIVE`, `VIP`, `budget`, `role`, `couleur`) VALUES
(5, 'Younes', '$2y$10$bNeUZxKhzrFwjhp.oxKkA.TInzixEaF4i/4Bw3ybh/wpY.VZyCHBy', 'younesysabri@hotmail.fr', 'uploads/images.jpg', 0, 0, 150, 'user', '#0c1b8c'),
(6, 'admin', '$2y$10$bNeUZxKhzrFwjhp.oxKkA.TInzixEaF4i/4Bw3ybh/wpY.VZyCHBy', 'admin@exemple.com', '', 1, 1, 0, 'admin', '#0c1b8c'),
(8, 'sabri', '$2y$10$ILsnMZIf9DRCxJxdQ93w1.6rmOZE/PMb3ZKbgUXcmaw6FCq6UBrey', 'younesysabr53i@gmail.com', 'uploads/télécharger.jpg', 1, 0, 1000, 'user', '#8d570c'),
(9, 'xpt', '$2y$10$UwpZO.PuGbGnSEpQTaAFI.GYCouFnilJ5Im64a8IJ4T1IHs7obdVe', 'younesysabri@gmail.com', 'uploads/avatar.jpg', 1, NULL, 200, 'user', '#0c1b8c'),
(10, 'Flav', '$2y$10$2BRLdWiU3ONtqF6/BqKTjOU3kQEz1QMzpgp63FCe5Wc/1xhbitt5i', 'maximilien.goffette@icloud.com', 'uploads/avatar.jpg', 1, 1, 1000, 'user', '#e6a519'),
(11, 'Flavie', '$2y$10$Tgjk7zjCcAQFlD1oTu9y/eRtaKENXeo4ioedXaAonIXK6NNInl9qG', 'flavie.cisse1@gmail.com', 'uploads/avatar.jpg', 1, 0, 1000, 'user', '#162bd0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
