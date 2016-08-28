-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 28 Août 2016 à 17:48
-- Version du serveur :  10.1.9-MariaDB
-- Version de PHP :  5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `kwaret_1.0.0`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `value`, `created`, `updated`) VALUES
(1, 'Etat civil', '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(2, 'Social', '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(3, 'Etudes', '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(4, 'Santé', '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(5, 'Logement', '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(6, 'Travail', '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(7, 'new category', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comments`
--

INSERT INTO `comments` (`id`, `value`, `created`, `updated`) VALUES
(2, 'commentaire du formulaire 1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'commentaire du formulaire 1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'ceci est un commentaire du formulaire 1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'commentaire du formulaire 2 ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'commentaire 1 du formulaire 2 ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'commentaire 2 du formulaire 2 ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'commentaire 3', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'commentaire  4', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'commentaire 5', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'nouveau comment', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'ceci est un commentaire', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) UNSIGNED NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `state` int(11) NOT NULL,
  `category_id` int(255) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forms`
--

INSERT INTO `forms` (`id`, `form_name`, `state`, `category_id`, `created`, `updated`) VALUES
(11, 'formulaire 1', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'formulaire 2', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'mon formulaire ', 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'formulaire 4', 0, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'formulaire 5', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'formulaire 6 ', 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `form_comment`
--

CREATE TABLE `form_comment` (
  `id` int(11) UNSIGNED NOT NULL,
  `form_id` int(11) UNSIGNED NOT NULL,
  `comment_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `form_comment`
--

INSERT INTO `form_comment` (`id`, `form_id`, `comment_id`) VALUES
(1, 11, 2),
(2, 11, 3),
(3, 11, 4),
(4, 12, 5),
(5, 12, 6),
(6, 12, 7);

-- --------------------------------------------------------

--
-- Structure de la table `form_tag`
--

CREATE TABLE `form_tag` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` text NOT NULL,
  `form_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `form_tag`
--

INSERT INTO `form_tag` (`id`, `value`, `form_id`, `tag_id`) VALUES
(1, '', 11, 23),
(2, '', 11, 24),
(3, '', 12, 25);

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `id_category` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tags`
--

INSERT INTO `tags` (`id`, `value`, `id_category`, `created`, `updated`) VALUES
(1, 'Carte nationale', 1, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(2, 'Passeport', 1, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(3, 'Voyage', 1, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(4, 'Visa', 0, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(5, 'Assurance', 4, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(6, 'Université', 3, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(7, 'Quitus', 3, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(8, 'Emploi', 6, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(9, 'Cnas', 6, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(10, 'Casnos', 4, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(11, 'Ansej', 6, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(12, 'Anem', 6, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(13, 'Mariage', 1, '2016-08-08 00:00:00', '2016-08-08 00:00:00'),
(14, 'new tag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'new tag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'new tag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'new new tag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'taaag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'taaag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'taaag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'taaag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'taaag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'taaag', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'tag de test', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'tagg form, 222', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `usr_id` int(11) UNSIGNED NOT NULL,
  `usr_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `usr_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `usrl_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lng_id` int(11) DEFAULT NULL,
  `usr_active` tinyint(1) NOT NULL,
  `usr_password_salt` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'dynamicSalt',
  `usr_registration_date` datetime DEFAULT NULL COMMENT 'Registration moment',
  `usr_registration_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'unique id sent by e-mail',
  `usr_email_confirmed` tinyint(1) NOT NULL COMMENT 'e-mail confirmed by user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`usr_id`, `usr_password`, `usr_email`, `usrl_id`, `lng_id`, `usr_active`, `usr_password_salt`, `usr_registration_date`, `usr_registration_token`, `usr_email_confirmed`) VALUES
(2, 'nawel15', 'naweeeeeeeeel@gmail.com', '1', 1, 1, 'Xh8aq\\4g5D[oi2Ax+kcN@HUM$i8D=(Z.e%!8Gw9`yEdsHa7nIR', '2016-07-30 00:08:08', '169a6b4c629a597a65a78f36712c1da6', 0),
(3, '20b2a32f158e33e04b4e5d82f3626669', 'nawelab@gmail.com', 'admin', 0, 0, 'pQKzT)p.h_2>5WwT+_$J2:~*]7h*4wlT*/*#$;*]6/l}H+^-l~', '2016-07-30 00:56:45', 'c7886623d757e6426dbcef2950b998c6', 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `form_comment`
--
ALTER TABLE `form_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`,`comment_id`);

--
-- Index pour la table `form_tag`
--
ALTER TABLE `form_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`,`tag_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
--
-- AUTO_INCREMENT pour la table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `form_comment`
--
ALTER TABLE `form_comment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `form_tag`
--
ALTER TABLE `form_tag`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `usr_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
