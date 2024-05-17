-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 17 mai 2024 à 10:59
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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(24, 4, 'epargne', 200.00, '2024-05-03', '');

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
  `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `budget` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `photo`, `budget`) VALUES
(1, 'sabri', '$2y$10$BcAmijfLGIJUYrQIuG5SAu8rDIYHVK1Zoyn4CgfYRiCWY8rTw7yxe', 'younesysabri@hotmail.fr', '', 0),
(2, 'younes', '$2y$10$9Ww1xfdO/CHM.qy4ozZsf.fzLkqs7vTaknIp1AaxVx6KCqGRBiiFa', 'younesysabri53@gmail.com', '', 0),
(4, 'Flavie', '$2y$10$5w5iDnKTUUWU1bj1JdKKdedoRajPcorJVc.qjsKpZSToP.ReJB4Je', 'flavie.cisse1@gmail.com', 'uploads/photo.png', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
