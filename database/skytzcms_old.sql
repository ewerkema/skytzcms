-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 10 sep 2016 om 08:22
-- Serverversie: 5.7.11
-- PHP-versie: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skytzcms`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_admins`
--

CREATE TABLE `skytz_admins` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `last_login` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_admins`
--

INSERT INTO `skytz_admins` (`id`, `username`, `password`, `email`, `last_login`) VALUES
(1, 'admin', '89f7774c46993970e74b1ec886032f1a6df009c46760b3ac3e37dd2222844626', 'info@skytz.nl', '27-07-2016 - 10:41:22'),
(2, 'dennis', '379ec8f2177206366a217554dc342cddeb2d90429d9cd886d251c09de941ec26', 'd.derks@skytz.nl', '11-02-2016 - 14:45:56');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_albumimages`
--

CREATE TABLE `skytz_albumimages` (
  `id` int(11) NOT NULL,
  `serverpath` text NOT NULL,
  `albumid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_albumimages`
--

INSERT INTO `skytz_albumimages` (`id`, `serverpath`, `albumid`) VALUES
(1, '/upload/uploads/images/579e11b3344e5.jpg', '1');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_albums`
--

CREATE TABLE `skytz_albums` (
  `id` int(11) NOT NULL,
  `albumname` text NOT NULL,
  `album_colorbox` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_albums`
--

INSERT INTO `skytz_albums` (`id`, `albumname`, `album_colorbox`) VALUES
(1, 'fdbfcbfg', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_blocks`
--

CREATE TABLE `skytz_blocks` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_blocks`
--

INSERT INTO `skytz_blocks` (`id`, `pageid`, `listorder`, `blockcontent`, `blockwidth`, `stroke`, `visible`, `stuck`, `module`, `module_id`) VALUES
(1, 1, 3, '<h1>Van harte welkom op onze demo pagina</h1>\n\n<p>&nbsp;</p>\n\n<p>Dit is de demo pagina van het Beheersysteem vahhhfhjb nn Skytz uit Winschoten. Op deze website kunt u de functionaliteiten vinden van ons systeem. Middels dit systeem kunt u zeer gemakkelijk uw eigen website beheren.</p>\n\n<p>Wilt u de tekst op uw website aanpassen? Dan kunt u rechtstreeks op deze tekst klikken om deze direct te bewerken. U blijft altijd uw website zien en ziet daarom altijd welke aanpassingen u maakt.a</p>\n\n<p><img alt="" src="/upload/uploads/images/auto.jpg" style="height:225px; width:400px" /></p>\n', 'small-12', 1, 0, 0, 0, ''),
(2, 1, 2, '', 'small-12', 1, 1, 0, 3, '1'),
(3, 1, 4, '', 'small-12', 1, 0, 0, 1, '1'),
(5, 2, 0, '<h2>Koptekst van deze pagina</h2>\n\n<p>De teksten zijn een vorm van pseudo-Latijn: ze lijken op het eerste gezicht origineel Latijn te zijn, maar hebben in werkelijkheid volstrekt geen betekenis. De tekst staat vol met spelfouten en verbasteringen. Dat is ook de reden waarom de teksten gebruikt worden door drukkers en zetters: bij een leesbare tekst zou de lezer afgeleid worden door de inhoud, terwijl het alleen om de vormgeving gaat. Bovendien heeft het Lorem ipsum een redelijk normale afwisseling van de verschillende letters en korte en lange woorden, waardoor het beter bruikbaar is dan bijvoorbeeld Dit is een voorbeeldtekst.hvhgjbghvgh</p>\n', 'small-12', 4, 0, 0, 0, ''),
(6, 1, 5, '', 'small-12', 1, 0, 0, 0, ''),
(7, 6, 0, '<p>Hallo test pagina</p>\n\n<ul>\n	<li><a href="http://cms.skytz.nl/cms_login/hallo-test">Hallo test</a></li>\n	<li><a href="http://cms.skytz.nl/cms_login/indeling-bewerken1">Indeling bewerken1</a></li>\n	<li><a href="http://cms.skytz.nl/cms_login/index">Beginpagina t</a></li>\n	<li><a href="http://cms.skytz.nl/cms_login/submenu-s">Submenu&#39;s</a></li>\n</ul>\n\n<p><img alt="" src="http://cms.skytz.nl/upload/uploads/slider/579e11b3344e5.jpg" /></p>\n\n<p>&nbsp;</p>\n', 'small-12', 8, 0, 0, 0, ''),
(8, 6, 1, '', 'small-12', 8, 0, 0, 3, '1'),
(9, 1, 1, '<p>bjguigiu</p>\n\n<p>&nbsp;</p>\n', 'small-12', 1, 0, 0, 0, ''),
(12, 5, 0, '', 'small-12', 12, 0, 0, 0, ''),
(13, 5, 0, '', 'small-12', 15, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_docs`
--

CREATE TABLE `skytz_docs` (
  `id` int(11) NOT NULL,
  `docpath` text NOT NULL,
  `uploaddate` text NOT NULL,
  `filesize` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `skytz_docs` (`id`, `docpath`, `uploaddate`, `filesize`) VALUES
(1, '/upload/uploads/docs/57d432f386c4a.docx', '10-09-2016 - 18:21:07', '11,37 KB');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_formelements`
--

CREATE TABLE `skytz_formelements` (
  `id` int(11) NOT NULL,
  `elementtype` text NOT NULL,
  `elementname` text NOT NULL,
  `elementoptions` text NOT NULL,
  `required` int(1) NOT NULL DEFAULT '0',
  `formid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `skytz_forms` (
  `id` int(11) NOT NULL,
  `formname` text NOT NULL,
  `formemail` text NOT NULL,
  `redirect` text NOT NULL,
  `thankyou_message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_forms`
--

INSERT INTO `skytz_forms` (`id`, `formname`, `formemail`, `redirect`, `thankyou_message`) VALUES
(1, 'Contact', 'd.derks@skytz.nl', '', 'Bedankt voor uw bericht!');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_images`
--

CREATE TABLE `skytz_images` (
  `id` int(11) NOT NULL,
  `imagepath` text NOT NULL,
  `uploaddate` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_images`
--

INSERT INTO `skytz_images` (`id`, `imagepath`, `uploaddate`) VALUES
(1, '/upload/uploads/images/579e11b3344e5.jpg', '10-02-2016 - 22:20:39'),
(2, '/upload/uploads/images/579e042da93ab.png', '10-02-2016 - 22:20:43');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_newsitems`
--

CREATE TABLE `skytz_newsitems` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `date` text NOT NULL,
  `newsid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_newsitems`
--

INSERT INTO `skytz_newsitems` (`id`, `title`, `content`, `date`, `newsid`) VALUES
(1, 'CMS is opgeschoont', '<p><span style="font-size:12px"><span style="font-family:arial,helvetica,sans-serif">Dit CMS is opgeschoont en heeft een paar updates gekregen!</span></span></p>\r\n\r\n<ol>\r\n	<li>Je kan als je inlogt bij de CMS nu op enter drukken (is nog een fout dat je 2x moet inloggen)</li>\r\n	<li>Nieuws werkt vanuit elke pagina</li>\r\n	<li>Index.php en loadwebcontent.php zijn samengevoegt in &eacute;&eacute;n (ook voor de /cms_login)</li>\r\n	<li>Paginabeheer heet nu Menubeheer, dit beheert het menu</li>\r\n	<li>Onnodige code en comments verwijderd</li>\r\n	<li>Nieuws heeft altijd een &#39;lees meer&#39; knop zodat je naar die pagina gaat</li>\r\n	<li>KCFinder geupdate</li>\r\n	<li>CKEditor geupdate, Thema op office 2013 gezet voor gebruikersgemak</li>\r\n	<li>Je kan de pagina titel veranderen zonder dat de website crashed</li>\r\n</ol>\r\n', '10-02-2016 - 23:31:29', '1');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_newssubjects`
--

CREATE TABLE `skytz_newssubjects` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_newssubjects`
--

INSERT INTO `skytz_newssubjects` (`id`, `title`) VALUES
(1, 'CMS Nieuws');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_pages`
--

CREATE TABLE `skytz_pages` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_pages`
--

INSERT INTO `skytz_pages` (`id`, `serverpath`, `pagetitle`, `metatitle`, `metadescr`, `menuitem`, `subitem`, `menuimage`, `pagehits`, `created`, `listorder`) VALUES
(1, 'index', 'Beginpagina t', 'Beginpagina mt', 'Beginpagina d', 1, 0, '', 270, '', 2),
(2, 'indeling-bewerken1', 'Indeling bewerken1', 'Indeling bewerken1', 'Indeling bewerken', 1, 0, '', 25, '10-02-2016 - 22:24:21', 0),
(3, 'submenu-s', 'Submenu\'s', 'Submenu\'s', 'Submenu\'s', 1, 0, '', 18, '10-02-2016 - 22:24:46', 3),
(4, 'submenu-item', 'Submenu item', 'Submenu item', 'Submenu item', 1, 3, '', 13, '10-02-2016 - 22:25:04', NULL),
(5, 'submenu-item-1', 'Submenu item 1', 'Submenu item 1', 'Submenu item 1', 1, 3, '', 22, '10-02-2016 - 22:25:18', NULL),
(6, 'hallo-test', 'Hallo test', 'Hallo test', '', 1, 0, '', 6, '31-05-2016 - 13:38:18', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_slider`
--

CREATE TABLE `skytz_slider` (
  `id` int(11) NOT NULL,
  `imageid` int(11) NOT NULL,
  `imagepath` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_slider`
--

INSERT INTO `skytz_slider` (`id`, `imageid`, `imagepath`) VALUES
(1, 2, '/upload/uploads/slider/579e042da93ab.png'),
(2, 1, '/upload/uploads/slider/579e11b3344e5.jpg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_strokes`
--

CREATE TABLE `skytz_strokes` (
  `id` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `widths` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `skytz_strokes`
--

INSERT INTO `skytz_strokes` (`id`, `pageid`, `widths`) VALUES
(1, 1, 'medium-6'),
(2, 1, 'medium-6'),
(3, 1, 'medium-6'),
(4, 2, 'medium-12'),
(5, 2, 'medium-12'),
(6, 1, 'medium-4'),
(7, 1, 'medium-12'),
(8, 6, 'medium-12'),
(9, 6, 'medium-2'),
(10, 1, 'medium-12'),
(11, 1, 'medium-2'),
(12, 5, 'medium-2'),
(13, 5, 'medium-4'),
(14, 5, 'medium-4'),
(15, 5, 'medium-2');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skytz_websettings`
--

CREATE TABLE `skytz_websettings` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `skytz_albumimages`
--
ALTER TABLE `skytz_albumimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_albums`
--
ALTER TABLE `skytz_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_blocks`
--
ALTER TABLE `skytz_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT voor een tabel `skytz_docs`
--
ALTER TABLE `skytz_docs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `skytz_formelements`
--
ALTER TABLE `skytz_formelements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT voor een tabel `skytz_forms`
--
ALTER TABLE `skytz_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_images`
--
ALTER TABLE `skytz_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `skytz_newsitems`
--
ALTER TABLE `skytz_newsitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_newssubjects`
--
ALTER TABLE `skytz_newssubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `skytz_pages`
--
ALTER TABLE `skytz_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT voor een tabel `skytz_slider`
--
ALTER TABLE `skytz_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT voor een tabel `skytz_strokes`
--
ALTER TABLE `skytz_strokes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT voor een tabel `skytz_websettings`
--
ALTER TABLE `skytz_websettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
