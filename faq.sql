-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: 127.0.0.1
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

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
-- Table structure for table `php_black_list`
--

DROP TABLE IF EXISTS `php_black_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php_black_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `php_black_list_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php_black_list`
--

LOCK TABLES `php_black_list` WRITE;
/*!40000 ALTER TABLE `php_black_list` DISABLE KEYS */;
INSERT INTO `php_black_list` VALUES (76,'vbn',1,'2018-12-17 18:18:32','2018-12-17 18:18:32'),(77,'ghj',1,'2018-12-17 18:18:32','2017-12-17 18:18:32'),(78,'klj',1,'2018-12-17 18:18:32','2018-12-17 18:18:32'),(79,'ffff',1,'2018-12-17 18:18:32','2018-12-17 18:18:32'),(80,'ggggg',1,'2018-12-17 18:18:32','2018-12-17 18:18:32');
/*!40000 ALTER TABLE `php_black_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php_categories`
--

DROP TABLE IF EXISTS `php_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `php_categories_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php_categories`
--

LOCK TABLES `php_categories` WRITE;
/*!40000 ALTER TABLE `php_categories` DISABLE KEYS */;
INSERT INTO `php_categories` VALUES (1,'Category1',1,'2018-12-03 13:00:41','2018-12-03 13:00:43'),(2,'Category2',1,'2018-12-03 13:00:55','2018-12-13 11:54:31'),(3,'Category3',1,'2018-12-03 13:00:55','2018-12-03 13:00:57'),(4,'Category4',1,'2018-12-17 18:19:26','2018-12-17 18:19:26');
/*!40000 ALTER TABLE `php_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `php_questions`
--

DROP TABLE IF EXISTS `php_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `author` text NOT NULL,
  `author_email` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `answer` mediumtext,
  `state` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `php_questions_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php_questions`
--


LOCK TABLES `php_questions` WRITE;
/*!40000 ALTER TABLE `php_questions` DISABLE KEYS */;
INSERT INTO `php_questions` VALUES (2,'dfgh gyhujk fghj ghjk gh?','Геннадий','dsf@fg.tu','2017-12-03 12:20:09','2017-12-17 18:18:00',2,1,'','ожидает ответ'),(8,'dfgh fgh ghj gh g hj ghj5?','Аскер','sdf@fds.dsf','2017-12-03 21:36:43','2017-12-09 18:56:36',3,1,'','ожидает ответ'),(9,'Как cvbhfghghj fgh fgh gh jghj ','Asdg','sdf@fds.dsf','2017-12-03 21:37:52','2017-12-09 18:56:13',3,1,'В fcvbftyghj fgjhkjk tfgyhjkrch.','опубликован'),(12,' cgvhb gjh h hgvh hg v?','Павел','sdf@fds.dsf','2018-12-05 22:20:27','2018-12-09 19:08:03',1,1,'cvg gfc g yjg ygм','опубликован'),(13,'vhj  jg hg g j y jgvhg?','gghh','hhhhhf@fds.dsf','2018-12-07 17:31:58','2018-12-09 18:28:07',2,1,'fbhjf h h ggh h vh gvhf gvh.','опубликован'),(14,'dfgujh fr  r rf fr  ','gfkjfb','sdf@fds.dsf','2018-12-07 17:32:53','2018-12-09 18:26:24',2,1,'bj jh  g g y uyg ','опубликован'),(16,'vhg gf gf f yfvS?','Виктор','fghb554@yand.ru','2018-12-07 17:52:48','2018-12-09 18:28:43',2,1,'C yvjhghvh hgv h jhgf hjgh ия.','опубликован'),(21,'В gfj ytf ft tf?','rtuhn','dsf@fg.tu','2018-12-08 09:09:01','2018-12-09 19:07:15',1,1,'gh fgh g hf ghf gbhn fvbn fgf gh','опубликован'),(25,'fghj vb cvb vbn?','ghkj','dsf@fg.tu','2018-12-09 17:14:09','2018-12-09 18:54:28',3,1,'HTgyu hbhv hgvhg vh gvh gv.','опубликован'),(26,' vg vhg vg cg hgjnkj 5?','Джейсон','fghj@fg.tu','2018-12-09 17:16:31','2018-12-09 18:55:52',3,1,'vgv hgvhv hvhvgа','опубликован'),(27,'hgvhjh hvh vh v?','ghj','dsf@fg.tu','2018-12-09 18:33:46','2018-12-09 18:34:24',2,1,'fggfh fhg hgf fg.','опубликован'),(28,'ahahahahah ahaha','ahah','aahaha@ah.ru','2018-12-09 19:04:13','2018-12-17 18:18:16',3,1,'','заблокирован'),(29,'bh hgv hg g hv h v?','gkug','dsf@fg.tu','2018-12-09 19:09:29','2018-12-09 19:28:02',1,1,' gv g jyg vjhvла.','опубликован'),(30,'Lghjgf gh gf gv ?','hgv','dsf@fg.tu','2018-12-09 19:10:21','2018-12-09 19:13:18',3,1,' hg hg jhghjgfh','опубликован'),(31,'gfg gv gj j?','fgjhgv','dgfhgvsf@fg.tu','2018-12-09 19:12:24','2018-12-09 19:13:42',3,1,'html','опубликован'),(32,'В hgvgh gv g ','hgkj','dsf@fg.tu','2018-12-09 19:27:54','2018-12-09 19:27:54',1,1,'Gf gf g gvg h hgv hgg.','опубликован'),(33,'Кgvg g g gchvggh ?','hgvh','dsf@fg.tu','2018-12-09 19:28:44','2018-12-09 19:28:44',1,1,'ghv gh g.','опубликован'),(34,'bg hg hg hg h v?','uhrf','fff@fg.tu','2018-12-09 19:29:09','2018-12-09 19:29:09',1,1,'Т gr g f fbgd tr grfvfdf е.','опубликован'),(35,'gghghj jg  gh gj ghj khjb?','hjlk','dsf@fg.tu','2018-12-09 19:29:25','2018-12-09 19:29:25',1,1,'','ожидает ответ'),(46,'vvv v vvvv v  dsfds','nnnnn','bbbbf@fg.tu','2018-12-17 18:20:22','2018-12-17 18:20:22',4,1,'','заблокирован'),(47,'no no no?','nonoi','dsf@fg.tu','2018-12-17 18:27:23','2018-12-17 18:27:23',4,1,'','скрыт');
/*!40000 ALTER TABLE `php_questions` ENABLE KEYS */;
UNLOCK TABLES;
--
-- Table structure for table `php_users`
--

DROP TABLE IF EXISTS `php_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `php_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `role` tinytext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `php_users`
--

LOCK TABLES `php_users` WRITE;
/*!40000 ALTER TABLE `php_users` DISABLE KEYS */;
INSERT INTO `php_users` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','admin','2018-08-25 21:58:09','2018-08-15 21:58:09');
/*!40000 ALTER TABLE `php_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-17 18:38:33