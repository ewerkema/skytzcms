-- phpMyAdmin SQL Dump
-- version 4.4.15
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Gegenereerd op: 28 mei 2016 om 10:15
-- Serverversie: 5.5.31
-- PHP-versie: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trucktrail_data`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_admins`
--

CREATE TABLE IF NOT EXISTS `skytz_admins` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `last_login` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_admins`
--

INSERT INTO `skytz_admins` (`id`, `username`, `password`, `email`, `last_login`) VALUES
(1, 'admin', '89f7774c46993970e74b1ec886032f1a6df009c46760b3ac3e37dd2222844626', 'info@skytz.nl', '28-05-2016 - 10:14:57'),
(2, 'dennis', '379ec8f2177206366a217554dc342cddeb2d90429d9cd886d251c09de941ec26', 'd.derks@skytz.nl', '11-02-2016 - 14:45:56');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_albumimages`
--

CREATE TABLE IF NOT EXISTS `skytz_albumimages` (
  `id` int(11) NOT NULL,
  `serverpath` text NOT NULL,
  `albumid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_albums`
--

CREATE TABLE IF NOT EXISTS `skytz_albums` (
  `id` int(11) NOT NULL,
  `albumname` text NOT NULL,
  `album_colorbox` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_blocks`
--

CREATE TABLE IF NOT EXISTS `skytz_blocks` (
  `id` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL DEFAULT '0',
  `blockcontent` text NOT NULL,
  `blockwidth` varchar(100) NOT NULL DEFAULT '20' COMMENT 'Width in foundation class',
  `stroke` int(11) NOT NULL DEFAULT '1',
  `visible` int(1) NOT NULL DEFAULT '1',
  `stuck` int(1) NOT NULL DEFAULT '0',
  `module` int(11) NOT NULL DEFAULT '0',
  `module_id` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_docs`
--

CREATE TABLE IF NOT EXISTS `skytz_docs` (
  `id` int(11) NOT NULL,
  `docpath` text NOT NULL,
  `uploaddate` text NOT NULL,
  `filesize` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_formelements`
--

CREATE TABLE IF NOT EXISTS `skytz_formelements` (
  `id` int(11) NOT NULL,
  `elementtype` text NOT NULL,
  `elementname` text NOT NULL,
  `elementoptions` text NOT NULL,
  `required` int(1) NOT NULL DEFAULT '0',
  `formid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_formelements`
--

INSERT INTO `skytz_formelements` (`id`, `elementtype`, `elementname`, `elementoptions`, `required`, `formid`) VALUES
(1, 'input', 'Naam', '', 0, 1),
(2, 'input', 'Telefoonnummer', '', 0, 1),
(3, 'textarea', 'Bericht', '', 0, 1),
(4, 'input', 'Email', '', 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_forms`
--

CREATE TABLE IF NOT EXISTS `skytz_forms` (
  `id` int(11) NOT NULL,
  `formname` text NOT NULL,
  `formemail` text NOT NULL,
  `redirect` text NOT NULL,
  `thankyou_message` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_forms`
--

INSERT INTO `skytz_forms` (`id`, `formname`, `formemail`, `redirect`, `thankyou_message`) VALUES
(1, 'Contact', 'd.derks@skytz.nl', '', 'Bedankt voor uw bericht!');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_images`
--

CREATE TABLE IF NOT EXISTS `skytz_images` (
  `id` int(11) NOT NULL,
  `imagepath` text NOT NULL,
  `uploaddate` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_newsitems`
--

CREATE TABLE IF NOT EXISTS `skytz_newsitems` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` text NOT NULL,
  `newsid` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_newsitems`
--

INSERT INTO `skytz_newsitems` (`id`, `title`, `content`, `date`, `newsid`) VALUES
(1, 'CMS is opgeschoont', '<p><span style="font-size:12px"><span style="font-family:arial,helvetica,sans-serif">Dit CMS is opgeschoont en heeft een paar updates gekregen!</span></span></p>\r\n\r\n<ol>\r\n	<li>Je kan als je inlogt bij de CMS nu op enter drukken (is nog een fout dat je 2x moet inloggen)</li>\r\n	<li>Nieuws werkt vanuit elke pagina</li>\r\n	<li>Index.php en loadwebcontent.php zijn samengevoegt in &eacute;&eacute;n (ook voor de /cms_login)</li>\r\n	<li>Paginabeheer heet nu Menubeheer, dit beheert het menu</li>\r\n	<li>Onnodige code en comments verwijderd</li>\r\n	<li>Nieuws heeft altijd een &#39;lees meer&#39; knop zodat je naar die pagina gaat</li>\r\n	<li>KCFinder geupdate</li>\r\n	<li>CKEditor geupdate, Thema op office 2013 gezet voor gebruikersgemak</li>\r\n	<li>Je kan de pagina titel veranderen zonder dat de website crashed</li>\r\n</ol>\r\n', '10-02-2016 - 23:31:29', '1');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_newssubjects`
--

CREATE TABLE IF NOT EXISTS `skytz_newssubjects` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_newssubjects`
--

INSERT INTO `skytz_newssubjects` (`id`, `title`) VALUES
(1, 'CMS Nieuws');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_pages`
--

CREATE TABLE IF NOT EXISTS `skytz_pages` (
  `id` int(11) NOT NULL,
  `serverpath` text NOT NULL,
  `pagetitle` text NOT NULL,
  `metatitle` text NOT NULL,
  `metadescr` text NOT NULL,
  `menuitem` int(1) NOT NULL DEFAULT '0',
  `subitem` int(11) NOT NULL DEFAULT '0',
  `menuimage` text NOT NULL,
  `pagehits` int(11) NOT NULL DEFAULT '0',
  `created` text NOT NULL,
  `listorder` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_pages`
--

INSERT INTO `skytz_pages` (`id`, `serverpath`, `pagetitle`, `metatitle`, `metadescr`, `menuitem`, `subitem`, `menuimage`, `pagehits`, `created`, `listorder`) VALUES
(1, 'index', 'Beginpagina t', 'Beginpagina mt', 'Beginpagina d', 1, 0, '', 140, '', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_slider`
--

CREATE TABLE IF NOT EXISTS `skytz_slider` (
  `id` int(11) NOT NULL,
  `imageid` int(11) NOT NULL,
  `imagepath` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_slider`
--

INSERT INTO `skytz_slider` (`id`, `imageid`, `imagepath`) VALUES
(1, 2, '/upload/uploads/slider/56bba9ab6cf7d.jpg'),
(2, 1, '/upload/uploads/slider/56bba9a785b94.jpg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_strokes`
--

CREATE TABLE IF NOT EXISTS `skytz_strokes` (
  `id` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `widths` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_websettings`
--

CREATE TABLE IF NOT EXISTS `skytz_websettings` (
  `id` int(11) NOT NULL,
  `resize_width` int(11) NOT NULL DEFAULT '0',
  `resize_height` int(11) NOT NULL DEFAULT '0',
  `facebook_page` text NOT NULL,
  `twitter_page` text NOT NULL,
  `footerblock` text NOT NULL,
  `meta_title` text NOT NULL,
  `meta_descr` text NOT NULL,
  `googleanalytics` text NOT NULL,
  `recordgoogle` int(1) NOT NULL DEFAULT '0',
  `redirict` text NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_websettings`
--

INSERT INTO `skytz_websettings` (`id`, `resize_width`, `resize_height`, `facebook_page`, `twitter_page`, `footerblock`, `meta_title`, `meta_descr`, `googleanalytics`, `recordgoogle`, `redirict`, `visible`) VALUES
(1, 100, 70, '', '', '', 'Skytz CMS', 'Website omschrijving komt hier te staan', '', 0, '', 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `skytz_admins`
--
ALTER TABLE `skytz_admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_albumimages`
--
ALTER TABLE `skytz_albumimages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_albums`
--
ALTER TABLE `skytz_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_blocks`
--
ALTER TABLE `skytz_blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_docs`
--
ALTER TABLE `skytz_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_formelements`
--
ALTER TABLE `skytz_formelements`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_forms`
--
ALTER TABLE `skytz_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_images`
--
ALTER TABLE `skytz_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_newsitems`
--
ALTER TABLE `skytz_newsitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_newssubjects`
--
ALTER TABLE `skytz_newssubjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_pages`
--
ALTER TABLE `skytz_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_slider`
--
ALTER TABLE `skytz_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_strokes`
--
ALTER TABLE `skytz_strokes`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skytz_websettings`
--
ALTER TABLE `skytz_websettings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `skytz_admins`
--
ALTER TABLE `skytz_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `skytz_albumimages`
--
ALTER TABLE `skytz_albumimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `skytz_albums`
--
ALTER TABLE `skytz_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `skytz_blocks`
--
ALTER TABLE `skytz_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT voor een tabel `skytz_docs`
--
ALTER TABLE `skytz_docs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `skytz_formelements`
--
ALTER TABLE `skytz_formelements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT voor een tabel `skytz_forms`
--
ALTER TABLE `skytz_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_images`
--
ALTER TABLE `skytz_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `skytz_newsitems`
--
ALTER TABLE `skytz_newsitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_newssubjects`
--
ALTER TABLE `skytz_newssubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_pages`
--
ALTER TABLE `skytz_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT voor een tabel `skytz_slider`
--
ALTER TABLE `skytz_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `skytz_strokes`
--
ALTER TABLE `skytz_strokes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT voor een tabel `skytz_websettings`
--
ALTER TABLE `skytz_websettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
