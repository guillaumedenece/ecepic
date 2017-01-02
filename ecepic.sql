-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 08 Mai 2016 à 23:40
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `ecepic`
--

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_owner` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `album`
--

INSERT INTO `album` (`ID`, `ID_owner`, `title`, `description`) VALUES
(16, 28, 'Voyage au Pérou', 'Voici quelques photos de mon incroyable Voyage au Pérou'),
(17, 29, 'Paysages', 'Quelques uns de mes paysages favoris...'),
(15, 28, 'Mon garage', 'Depuis mon plus jeune age, je suis passionné de belles voitures. Je suis aujourd''hui fier de vous présenter celles que j''ai pu collectionner depuis 10 ans...');

-- --------------------------------------------------------

--
-- Structure de la table `album_photo`
--

CREATE TABLE IF NOT EXISTS `album_photo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_photo` int(11) NOT NULL,
  `ID_album` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `album_photo`
--

INSERT INTO `album_photo` (`ID`, `ID_photo`, `ID_album`) VALUES
(26, 31, 17),
(25, 36, 17),
(24, 31, 16),
(23, 30, 16),
(22, 32, 16),
(21, 26, 15),
(20, 25, 15),
(19, 24, 15);

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `public` tinyint(1) NOT NULL,
  `ID_owner` text NOT NULL,
  `location` text NOT NULL,
  `date` datetime NOT NULL,
  `theme` text NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Contenu de la table `photos`
--

INSERT INTO `photos` (`ID`, `path`, `public`, `ID_owner`, `location`, `date`, `theme`, `name`) VALUES
(24, 'photos/4.jpg', 1, '28', 'London, England', '2016-05-08 13:25:43', 'Voiture', 'Aston Martin DBS'),
(25, 'photos/fefe1.jpg', 1, '28', 'Paris, France', '2016-05-08 22:46:44', 'Voiture', 'Ferrari F12'),
(26, 'photos/porsche.jpg', 1, '28', 'New York, Etats Unis', '2016-05-08 22:48:11', 'Voiture', 'Porsche Carrera 4S'),
(30, 'photos/perou1.jpg', 1, '28', 'Lima, Pérou', '2016-05-08 23:22:34', 'Paysage', 'Pérou 1'),
(31, 'photos/perou2.jpg', 1, '28', 'Lima, Pérou', '2016-05-08 23:29:49', 'Paysage', 'Pérou 2'),
(32, 'photos/perou3.jpg', 1, '28', 'Machu Picchu, Pérou', '2016-05-08 23:30:36', 'Paysage', 'Pérou 3'),
(36, 'photos/IMG_0859.JPG', 1, '29', 'Fraser island, Australia', '2016-05-08 23:36:27', 'Paysage', 'Ile paradisiaque');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` text NOT NULL,
  `mdp` text NOT NULL,
  `email` text NOT NULL,
  `avatar` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`ID`, `pseudo`, `mdp`, `email`, `avatar`, `admin`) VALUES
(28, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@admin.com', 'avatars/admin.png', 1),
(29, 'guillaume', '2450ec3ccdf9492f0296810ab160876644aa9cff', 'guillaume.denece@gmail.com', 'avatars/guillaume.jpg', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
