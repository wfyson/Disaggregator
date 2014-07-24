-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: disaggregator
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `author` (
  `AuthorID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`AuthorID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Will Fyson',1);
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compound`
--

DROP TABLE IF EXISTS `compound`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compound` (
  `CompoundID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `MolFile` varchar(255) NOT NULL,
  PRIMARY KEY (`CompoundID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compound`
--

LOCK TABLES `compound` WRITE;
/*!40000 ALTER TABLE `compound` DISABLE KEYS */;
INSERT INTO `compound` VALUES (2,'N-(Dec-9-en-3-yn-1-yl)-N-(hex-1-yn-1-yl)-4-methylbenzenesulfonamide','Compound 131b in A.Henderson\'s thesis','Structure_131b.mol'),(3,'N-(Hex-1-yn-1-yl)-N-(2-(hex-1-yn-1-yl)phenyl)-4-methylbenzenesulfonamide','Compound 110b in A.Henderson\'s thesis','Structure_110b.mol');
/*!40000 ALTER TABLE `compound` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compound_reference`
--

DROP TABLE IF EXISTS `compound_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compound_reference` (
  `CompoundReferenceID` int(11) NOT NULL AUTO_INCREMENT,
  `CompoundID` int(11) NOT NULL,
  `ReferenceID` int(11) NOT NULL,
  PRIMARY KEY (`CompoundReferenceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compound_reference`
--

LOCK TABLES `compound_reference` WRITE;
/*!40000 ALTER TABLE `compound_reference` DISABLE KEYS */;
INSERT INTO `compound_reference` VALUES (1,2,1);
/*!40000 ALTER TABLE `compound_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compound_spectra`
--

DROP TABLE IF EXISTS `compound_spectra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compound_spectra` (
  `CompoundSpectraID` int(11) NOT NULL AUTO_INCREMENT,
  `CompoundID` int(11) NOT NULL,
  `SpectraID` int(11) NOT NULL,
  PRIMARY KEY (`CompoundSpectraID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compound_spectra`
--

LOCK TABLES `compound_spectra` WRITE;
/*!40000 ALTER TABLE `compound_spectra` DISABLE KEYS */;
INSERT INTO `compound_spectra` VALUES (1,2,1);
/*!40000 ALTER TABLE `compound_spectra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compound_tag`
--

DROP TABLE IF EXISTS `compound_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compound_tag` (
  `CompoundTagID` int(11) NOT NULL AUTO_INCREMENT,
  `CompoundID` int(11) NOT NULL,
  `TagID` int(11) NOT NULL,
  PRIMARY KEY (`CompoundTagID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compound_tag`
--

LOCK TABLES `compound_tag` WRITE;
/*!40000 ALTER TABLE `compound_tag` DISABLE KEYS */;
INSERT INTO `compound_tag` VALUES (1,2,1);
/*!40000 ALTER TABLE `compound_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction`
--

DROP TABLE IF EXISTS `reaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reaction` (
  `ReactionID` int(11) NOT NULL AUTO_INCREMENT,
  `Transformation` varchar(255) NOT NULL,
  `Result` int(11) NOT NULL,
  `Procedure` mediumtext NOT NULL,
  PRIMARY KEY (`ReactionID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction`
--

LOCK TABLES `reaction` WRITE;
/*!40000 ALTER TABLE `reaction` DISABLE KEYS */;
INSERT INTO `reaction` VALUES (1,'Zirconocene mediated cocyclisations of ynamides',2,'General procedure A (see below) with N-(Dec-9-en-3-yn-1-yl)-4-methylbenzenesulfonamide (706 mg, 2.3 mmol) and 1-Bromohex-1-yne (403 mg, 2.5 mmol).  Purification by column chromatography (SiO2, hexane/Et2O 9:1) gave the title compound as a yellow oil (628 mg, 71%).\r\n\r\n \r\n\r\n \r\n\r\nGeneral Procedure A:\r\n\r\nA solution of the appropriate sulfonamide (1 eq.) and the appropriate bromide (1.1 eq.) in DMF (35 mL) was added to K2CO3 (2 eq.), CuSO4.5H2O (0.1 eq.) and 1,10-phenanthroline (0.2 eq.).  The reaction mixture was stirred at 65 °C for 16 h.  The reaction mixture was poured into water (200 mL) and extracted with ether (3 × 50 mL).  The organic extracts were combined, washed with water (2 × 100 mL), dried (MgSO4), filtered and the solvent removed in vacuo to give the crude product, which was purified as described.');
/*!40000 ALTER TABLE `reaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_author`
--

DROP TABLE IF EXISTS `reaction_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reaction_author` (
  `ReactionAuthorID` int(11) NOT NULL AUTO_INCREMENT,
  `ReactionID` int(11) NOT NULL,
  `AuthorID` int(11) NOT NULL,
  `Role` varchar(45) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  PRIMARY KEY (`ReactionAuthorID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_author`
--

LOCK TABLES `reaction_author` WRITE;
/*!40000 ALTER TABLE `reaction_author` DISABLE KEYS */;
INSERT INTO `reaction_author` VALUES (1,1,1,'Doer','This was great fun!');
/*!40000 ALTER TABLE `reaction_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_reference`
--

DROP TABLE IF EXISTS `reaction_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reaction_reference` (
  `ReactionReferenceID` int(11) NOT NULL AUTO_INCREMENT,
  `ReactionID` int(11) NOT NULL,
  `ReferenceID` int(11) NOT NULL,
  PRIMARY KEY (`ReactionReferenceID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_reference`
--

LOCK TABLES `reaction_reference` WRITE;
/*!40000 ALTER TABLE `reaction_reference` DISABLE KEYS */;
INSERT INTO `reaction_reference` VALUES (1,1,1);
/*!40000 ALTER TABLE `reaction_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reaction_tag`
--

DROP TABLE IF EXISTS `reaction_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reaction_tag` (
  `ReactionTagID` int(11) NOT NULL AUTO_INCREMENT,
  `ReactionID` int(11) NOT NULL,
  `TagID` int(11) NOT NULL,
  PRIMARY KEY (`ReactionTagID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reaction_tag`
--

LOCK TABLES `reaction_tag` WRITE;
/*!40000 ALTER TABLE `reaction_tag` DISABLE KEYS */;
INSERT INTO `reaction_tag` VALUES (1,1,2);
/*!40000 ALTER TABLE `reaction_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reference` (
  `ReferenceID` int(11) NOT NULL AUTO_INCREMENT,
  `RefFile` varchar(255) NOT NULL,
  `UploaderID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ReferenceID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reference`
--

LOCK TABLES `reference` WRITE;
/*!40000 ALTER TABLE `reference` DISABLE KEYS */;
INSERT INTO `reference` VALUES (17,'disaggregator_test.docx',1),(18,'transfer4.docx',1);
/*!40000 ALTER TABLE `reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spectra`
--

DROP TABLE IF EXISTS `spectra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spectra` (
  `SpectraID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(100) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `JCAMPFile` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  PRIMARY KEY (`SpectraID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spectra`
--

LOCK TABLES `spectra` WRITE;
/*!40000 ALTER TABLE `spectra` DISABLE KEYS */;
INSERT INTO `spectra` VALUES (1,'HNMR','1H NMR: dH (300 MHz, CDCl3) 7.71 (2H, d, J 8.1 Hz, H-18), 7.25 (2H, d, J 8.1 Hz, H?19), 5.72 (1H, ddt, J 17.0, 10.3, 6.6 Hz, H-15), 5.03–4.74 (2H, m, H-16), 3.33 (2H, t, J 8.1 Hz, H-7), 2.46–2.29 (2H, m, H-8), 2.37 (3H, s, H-21), 2.17 (2H, t, J 7.0 Hz, H-','HNMR_131b.jdx','HNMR_131b.pdf');
/*!40000 ALTER TABLE `spectra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `TagID` int(11) NOT NULL AUTO_INCREMENT,
  `Keyword` varchar(45) NOT NULL,
  PRIMARY KEY (`TagID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES (1,'acid'),(2,'oxidation');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'rwf1v07','$2y$10$nfB3KXYZSFtrlXZ.N6TsV./AY76ZHk8l3ZixG175EMWgg.7iQu/Te','rwf1v07@test.ac.uk'),(2,'test','$2y$10$riFqQ0/UzvLbTLWghGtIaeDqd6onxoY5x6XFCSBP7SK/3qdMNNL36','testing@blah.ac.uk');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-07-24 15:19:19
