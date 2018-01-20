USE `cmgdemo`;

SET FOREIGN_KEY_CHECKS=0;

/* ============================= CMS Gears Forum ============================================== */

--
-- Table structure for table `cmg_forum`
--

DROP TABLE IF EXISTS `cmg_forum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_forum` (
  `forum_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `forum_owner` bigint(20) DEFAULT NULL,
  `forum_banner` bigint(20) DEFAULT NULL,
  `forum_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forum_desc` varchar(225) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forum_slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `forum_created_on` datetime DEFAULT NULL,
  `forum_updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`forum_id`),
  UNIQUE KEY `forum_slug_unique` (`forum_slug`),
  KEY `fk_forum_1` (`forum_owner`),
  KEY `fk_forum_2` (`forum_banner`),
  CONSTRAINT `fk_forum_1` FOREIGN KEY (`forum_owner`) REFERENCES `cmg_user` (`user_id`),
  CONSTRAINT `fk_forum_2` FOREIGN KEY (`forum_banner`) REFERENCES `cmg_file` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_forum_category`
--

DROP TABLE IF EXISTS `cmg_forum_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_forum_category` (
  `forum_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  PRIMARY KEY (`forum_id`,`category_id`),
  KEY `fk_forum_category_1` (`forum_id`),
  KEY `fk_forum_category_2` (`category_id`),
  CONSTRAINT `fk_forum_category_1` FOREIGN KEY (`forum_id`) REFERENCES `cmg_forum` (`forum_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_forum_category_2` FOREIGN KEY (`category_id`) REFERENCES `cmg_category` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_forum_topic`
--

DROP TABLE IF EXISTS `cmg_forum_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_forum_topic` (
  `topic_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `topic_forum` bigint(20) DEFAULT NULL,
  `topic_owner` bigint(20) DEFAULT NULL,
  `topic_name` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `topic_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `topic_created_on` datetime DEFAULT NULL,
  `topic_updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `fk_forum_topic_1` (`topic_forum`),
  KEY `fk_forum_topic_2` (`topic_owner`),
  CONSTRAINT `fk_forum_topic_1` FOREIGN KEY (`topic_forum`) REFERENCES `cmg_forum` (`forum_id`),
  CONSTRAINT `fk_forum_topic_2` FOREIGN KEY (`topic_owner`) REFERENCES `cmg_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_forum_reply`
--

DROP TABLE IF EXISTS `cmg_forum_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_forum_reply` (
  `reply_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reply_forum` bigint(20) DEFAULT NULL,
  `reply_topic` bigint(20) NOT NULL,
  `reply_owner` bigint(20) DEFAULT NULL,
  `reply_content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reply_created_on` datetime DEFAULT NULL,
  `reply_updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`reply_id`),
  KEY `fk_forum_reply_1` (`reply_forum`),
  KEY `fk_forum_reply_2` (`reply_topic`),
  KEY `fk_forum_reply_3` (`reply_owner`),
  CONSTRAINT `fk_forum_reply_1` FOREIGN KEY (`reply_forum`) REFERENCES `cmg_forum` (`forum_id`),
  CONSTRAINT `fk_forum_reply_2` FOREIGN KEY (`reply_topic`) REFERENCES `cmg_forum_topic` (`topic_id`),
  CONSTRAINT `fk_forum_reply_3` FOREIGN KEY (`reply_owner`) REFERENCES `cmg_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET FOREIGN_KEY_CHECKS=1;