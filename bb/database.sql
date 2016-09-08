-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Mer 07 Septembre 2016 à 06:38
-- Version du serveur :  5.5.49-0+deb8u1
-- Version de PHP :  7.0.7-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `code-vote`
--

-- --------------------------------------------------------

--
-- Structure de la table `apps`
--

CREATE TABLE `apps` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `template` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `flags` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `apps`
--

INSERT INTO `apps` (`id`, `path`, `template`, `type`, `flags`) VALUES
(1, 'errors.404', NULL, 'text/html; charset=UTF-8', 2),
(2, 'errors.503', NULL, 'text/html; charset=UTF-8', 2),
(3, 'home', NULL, 'text/html; charset=UTF-8', 3),
(4, 'encrypt', NULL, 'text/plain; charset=UTF-8', 0),
(5, 'post', NULL, 'text/plain; charset=UTF-8', 0);

-- --------------------------------------------------------

--
-- Structure de la table `constants`
--

CREATE TABLE `constants` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `evaluate` tinyint(1) UNSIGNED NOT NULL,
  `javaScript` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `constants`
--

INSERT INTO `constants` (`id`, `path`, `name`, `value`, `evaluate`, `javaScript`) VALUES
(1, 'classes.CodeVote', 'DEFAULT_LOCALE', 'fr_FR', 0, 0),
(2, 'classes.CodeVote', 'APPS_AVAILABILITY', 'true', 1, 0),
(3, 'classes.CodeVote', 'APPS_INDEX', 'true', 1, 0),
(4, 'classes.CodeVote', 'APPS_FOLLOW', 'true', 1, 0),
(5, 'classes.CodeVote', 'APPS_FLAG_INDEX', '1', 1, 0),
(6, 'classes.CodeVote', 'APPS_FLAG_FOLLOW', '2', 1, 0),
(7, 'classes.CodeVote', 'APPS_TOOL', '/home/belenios/bin/_build/belenios-tool', 0, 0),
(8, 'classes.CodeVote', 'APPS_DIR', '/home/bb/code.vote/www/pub', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `locales`
--

CREATE TABLE `locales` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(5) NOT NULL,
  `value` varchar(5) NOT NULL,
  `language` char(2) NOT NULL,
  `timezone` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `locales`
--

INSERT INTO `locales` (`id`, `name`, `value`, `language`, `timezone`) VALUES
(1, 'fr-fr', 'fr_FR', 'fr', 'Europe/Paris');

-- --------------------------------------------------------

--
-- Structure de la table `texts`
--

CREATE TABLE `texts` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `minimum` tinyint(4) DEFAULT NULL,
  `maximum` tinyint(4) DEFAULT NULL,
  `context` varchar(255) DEFAULT NULL,
  `fr-fr` text NOT NULL,
  `javaScript` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `texts`
--

INSERT INTO `texts` (`id`, `path`, `name`, `minimum`, `maximum`, `context`, `fr-fr`, `javaScript`) VALUES
(1, 'apps.home', 'invalidAnswer', 1, 1, 'equal', 'Vous devez sélectionner $max réponse.', 0),
(2, 'apps.home', 'invalidAnswer', 1, 1, 'different', 'Vous devez sélectionner entre $min et $max réponse.', 0),
(3, 'apps.home', 'invalidAnswer', 2, NULL, 'equal', 'Vous devez sélectionner $max réponses.', 0),
(4, 'apps.home', 'invalidAnswer', 2, NULL, 'different', 'Vous devez sélectionner entre $min et $max réponses.', 0);

-- --------------------------------------------------------

--
-- Structure de la table `timezones`
--

CREATE TABLE `timezones` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `timezones`
--

INSERT INTO `timezones` (`id`, `value`) VALUES
(1, 'Europe/Paris');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `apps`
--
ALTER TABLE `apps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`);

--
-- Index pour la table `constants`
--
ALTER TABLE `constants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `path` (`path`);

--
-- Index pour la table `locales`
--
ALTER TABLE `locales`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `path` (`path`);

--
-- Index pour la table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `apps`
--
ALTER TABLE `apps`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `constants`
--
ALTER TABLE `constants`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `locales`
--
ALTER TABLE `locales`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `texts`
--
ALTER TABLE `texts`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
