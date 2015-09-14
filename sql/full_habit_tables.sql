-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: habit
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `habit_description`
--

DROP TABLE IF EXISTS `habit_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habit_description` (
  `habit_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `habit_description` varchar(128) DEFAULT NULL,
  `info` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`habit_id`),
  CONSTRAINT `habit_description_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_main` (`habit_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `habit_main`
--

DROP TABLE IF EXISTS `habit_main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habit_main` (
  `habit_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `habit_name` varchar(32) DEFAULT NULL,
  `insert_date` datetime DEFAULT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`habit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `habit_score`
--

DROP TABLE IF EXISTS `habit_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habit_score` (
  `habit_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `habit_level` tinyint(4) NOT NULL DEFAULT '1',
  `habit_experience` smallint(5) unsigned NOT NULL DEFAULT '0',
  `leveled_up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`habit_id`),
  CONSTRAINT `habit_score_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_main` (`habit_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `habit_tracker`
--

DROP TABLE IF EXISTS `habit_tracker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habit_tracker` (
  `habit_name` varchar(32) DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` smallint(5) unsigned NOT NULL DEFAULT '1',
  `completion` smallint(5) unsigned NOT NULL DEFAULT '1',
  `habit_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`habit_id`),
  CONSTRAINT `habit_tracker_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_main` (`habit_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `habits_over_time`
--

DROP TABLE IF EXISTS `habits_over_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `habits_over_time` (
  `habit_id` mediumint(8) unsigned DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `prioritized` tinyint(1) NOT NULL DEFAULT '0',
  `urgency` float NOT NULL DEFAULT '1',
  `time_of_entry` datetime NOT NULL DEFAULT '1920-02-29 13:13:13',
  `habit_level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `habit_experience` smallint(5) unsigned NOT NULL DEFAULT '0',
  `leveled_up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `habit_id` (`habit_id`),
  CONSTRAINT `habits_over_time_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habit_main` (`habit_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice` (
  `nid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `category` char(4) NOT NULL DEFAULT 'xxxx',
  `sticky_note` varchar(32) DEFAULT NULL,
  `big_note` varchar(1024) DEFAULT NULL,
  `goalset` datetime NOT NULL,
  `complete` datetime DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `a` int(11) DEFAULT NULL,
  `b` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-13 22:20:14
