-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 22 mai 2024 à 15:25
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
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(39, 5, 'alimentation', 25.00, '2024-05-23', ''),
(38, 5, 'alimentation', 25.00, '2024-05-23', ''),
(36, 5, 'alimentation', 25.00, '2024-05-30', ''),
(35, 5, 'alimentation', 2129.00, '2024-05-29', ''),
(37, 5, 'alimentation', 30.00, '2024-05-16', '');

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
  `role` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `photo`, `ACTIVE`, `VIP`, `budget`, `role`) VALUES
(5, 'Younes', '$2y$10$bNeUZxKhzrFwjhp.oxKkA.TInzixEaF4i/4Bw3ybh/wpY.VZyCHBy', 'younesysabri@hotmail.fr', 'uploads/images.jpg', 0, 0, 150, 'user'),
(6, 'admin', '$2y$10$bNeUZxKhzrFwjhp.oxKkA.TInzixEaF4i/4Bw3ybh/wpY.VZyCHBy', 'admin@exemple.com', '', 1, 1, 0, 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
