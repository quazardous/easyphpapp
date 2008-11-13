-- phpMyAdmin SQL Dump
-- version 2.11.1.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 12 Novembre 2008 à 16:26
-- Version du serveur: 5.0.45
-- Version de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `ea_test_table1`
--

CREATE TABLE IF NOT EXISTS `ea_test_table1` (
  `id` bigint(20) NOT NULL auto_increment,
  `valint` int(11) NOT NULL,
  `valdate` date NOT NULL default '2008-11-22',
  `valenum` enum('one','two','three') NOT NULL,
  `valtinyint` tinyint(4) NOT NULL,
  `valsmallint` smallint(6) NOT NULL,
  `valvarchar` varchar(32) NOT NULL,
  `valmediumint` mediumint(9) NOT NULL,
  `valfloat` float NOT NULL default '0',
  `valdouble` double NOT NULL,
  `valdecimal` decimal(10,2) NOT NULL,
  `valdatetime` datetime NOT NULL,
  `valtimestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `valtime` time NOT NULL,
  `valyear` year(4) NOT NULL,
  `valchar` char(32) NOT NULL,
  `valtinyblob` tinyblob NOT NULL,
  `valtinytext` tinytext NOT NULL,
  `valblob` blob NOT NULL,
  `vamediumblob` mediumblob NOT NULL,
  `valmediumtext` mediumtext NOT NULL,
  `vallongblob` longblob NOT NULL,
  `vallongtext` longtext NOT NULL,
  `valbool` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `ea_test_table1`
--

INSERT INTO `ea_test_table1` (`id`, `valint`, `valdate`, `valenum`, `valtinyint`, `valsmallint`, `valvarchar`, `valmediumint`, `valfloat`, `valdouble`, `valdecimal`, `valdatetime`, `valtimestamp`, `valtime`, `valyear`, `valchar`, `valtinyblob`, `valtinytext`, `valblob`, `vamediumblob`, `valmediumtext`, `vallongblob`, `vallongtext`, `valbool`) VALUES
(1, 0, '0000-00-00', 'one', 1, 10, 'azerty', 100, 3.14159, 3.14159265358979, 3.14, '2008-11-13 00:00:00', '2008-11-12 16:25:21', '00:00:00', 2008, 'azerty', '', 'blah blah', '', '', 'blah blah', '', 'blah blah', 0),
(2, 0, '0000-00-00', 'one', 1, 10, 'azerty', 100, 3.14159, 3.14159265358979, 3.14, '2008-11-13 00:00:00', '2008-11-12 16:25:38', '00:00:00', 2008, 'azerty', '', 'blah blah', '', '', 'blah blah', '', 'blah blah', 0);
