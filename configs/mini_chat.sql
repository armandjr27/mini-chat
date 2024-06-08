-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 08, 2024 at 01:25 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
CREATE TABLE IF NOT EXISTS `conversations` (
  `id_conversation` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_conversation`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id_conversation`, `title`, `created_at`) VALUES
(1, 'Spoon Consulting Recruitment', '2024-05-15 20:07:58'),
(2, 'PHP', '2024-05-15 21:05:55'),
(6, 'My Homes', '2024-05-18 12:05:38'),
(4, 'HTML', '2024-05-15 21:20:25'),
(5, 'CSS', '2024-05-15 21:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_general_ci,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `conversation_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`),
  KEY `fk_messages_conversations_idx` (`conversation_id`),
  KEY `fk_messages_users1_idx` (`receiver_id`),
  KEY `fk_messages_users2_idx` (`sender_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id_message`, `content`, `sender_id`, `receiver_id`, `conversation_id`, `created_at`) VALUES
(1, 'Bonjour !', 9, 8, 1, '2024-05-15 20:07:58'),
(2, 'yes! man', 8, 9, 1, '2024-05-15 20:15:17'),
(3, 'Howdy !', 8, 9, 2, '2024-05-15 21:05:55'),
(4, 'Howdy !', 8, 9, 3, '2024-05-15 21:05:55'),
(5, 'Bonjour !', 8, 9, 4, '2024-05-15 21:20:25'),
(6, 'Bonjour !', 8, 9, 5, '2024-05-15 21:22:33'),
(7, 'salut', 0, 8, 4, '2024-05-15 22:13:52'),
(8, 'salut', 9, 8, 5, '2024-05-15 22:19:16'),
(9, 'sava', 9, 8, 2, '2024-05-15 22:23:15'),
(10, 'Et alor?', 9, 8, 1, '2024-05-15 22:26:42'),
(11, 'sava', 8, 9, 5, '2024-05-15 22:30:40'),
(12, 'Bonjour !', 9, 8, 6, '2024-05-18 12:05:39'),
(13, 'sava', 9, 8, 6, '2024-05-18 12:05:55'),
(14, 'sava', 8, 9, 6, '2024-05-18 12:06:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'profil.jpg',
  `pseudo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `join_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `pseudo_UNIQUE` (`pseudo`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `profil`, `pseudo`, `email`, `password`, `join_at`) VALUES
(8, 'profil.jpg', 'kygo', 'kygo@gmail.com', '$2y$10$8V5JmBqPAlyCjiryzAQvVeGjeWTd2a/z7L35aEWmze/JKF5QryjOC', '2024-04-18 23:38:59'),
(9, 'profil.jpg', 'armandJr', 'armandrmrsn@gmail.com', '$2y$10$P6jrudB20qaxEU6XvEt2xutm062Sg.U019dcrq4cbPbwrFKWjNIje', '2024-04-19 20:42:15');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
