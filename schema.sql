-- MySQL dump 10.13  Distrib 5.6.25, for debian-linux-gnu (x86_64)
--
-- Host: 192.168.1.15    Database: pipedot
-- ------------------------------------------------------
-- Server version	5.6.25-0ubuntu1

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
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article` (
  `article_id` int(11) NOT NULL,
  `author_link` varchar(200) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `description` text NOT NULL,
  `feed_html` text NOT NULL,
  `feed_id` int(11) NOT NULL,
  `guid` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `redirect_link` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `thumb_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `article_guid` (`guid`),
  KEY `article_feed` (`feed_id`,`publish_time`),
  FULLTEXT KEY `title_search` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `avatar`
--

DROP TABLE IF EXISTS `avatar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avatar` (
  `avatar_id` int(11) NOT NULL,
  `hash_64` varchar(64) NOT NULL,
  `hash_128` varchar(64) NOT NULL,
  `hash_256` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`avatar_id`),
  UNIQUE KEY `avatar_id_UNIQUE` (`avatar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ban_ip`
--

DROP TABLE IF EXISTS `ban_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ban_ip` (
  `remote_ip` varchar(45) NOT NULL,
  `short_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`remote_ip`),
  UNIQUE KEY `remote_ip_UNIQUE` (`remote_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ban_user`
--

DROP TABLE IF EXISTS `ban_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ban_user` (
  `zid` varchar(50) NOT NULL,
  `short_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `editor_zid` varchar(50) NOT NULL,
  PRIMARY KEY (`zid`),
  UNIQUE KEY `zid_UNIQUE` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bug`
--

DROP TABLE IF EXISTS `bug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bug` (
  `bug_id` int(11) NOT NULL,
  `author_zid` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `closed` int(11) NOT NULL,
  `closed_zid` varchar(50) NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `priority` varchar(20) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`bug_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bug_file`
--

DROP TABLE IF EXISTS `bug_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bug_file` (
  `bug_file_id` int(11) NOT NULL,
  `bug_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `name` varchar(100) NOT NULL,
  `remote_ip` varchar(45) NOT NULL,
  `server` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`bug_file_id`),
  UNIQUE KEY `short_id_UNIQUE` (`bug_file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bug_label`
--

DROP TABLE IF EXISTS `bug_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bug_label` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `label_name` varchar(20) NOT NULL,
  `label_tag` varchar(20) NOT NULL,
  `background_color` varchar(7) NOT NULL,
  `foreground_color` varchar(7) NOT NULL,
  `reportable` int(11) NOT NULL,
  PRIMARY KEY (`label_id`),
  UNIQUE KEY `label_id_UNIQUE` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bug_labels`
--

DROP TABLE IF EXISTS `bug_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bug_labels` (
  `bug_id` int(11) NOT NULL,
  `label_id` int(11) NOT NULL,
  KEY `bug_label_bug_id` (`bug_id`),
  KEY `bug_label_label_id` (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `cache_id` int(11) NOT NULL AUTO_INCREMENT,
  `access_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `data_hash` varchar(64) NOT NULL,
  `url` varchar(250) NOT NULL,
  `url_hash` varchar(64) NOT NULL,
  PRIMARY KEY (`cache_id`),
  UNIQUE KEY `cache_id_UNIQUE` (`url_hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `captcha`
--

DROP TABLE IF EXISTS `captcha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `captcha` (
  `captcha_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(250) NOT NULL,
  `answer` varchar(250) NOT NULL,
  PRIMARY KEY (`captcha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `captcha_challenge`
--

DROP TABLE IF EXISTS `captcha_challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `captcha_challenge` (
  `remote_ip` varchar(100) NOT NULL,
  `captcha_id` int(11) NOT NULL,
  PRIMARY KEY (`remote_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card` (
  `card_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `edit_time` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `link_subject` varchar(200) NOT NULL,
  `link_url` varchar(200) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `card_edit`
--

DROP TABLE IF EXISTS `card_edit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_edit` (
  `card_id` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `edit_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `card_tags`
--

DROP TABLE IF EXISTS `card_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_tags` (
  `card_id` int(11) NOT NULL,
  `tag` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `clean` int(11) NOT NULL,
  `edit_time` int(11) NOT NULL,
  `junk_status` int(11) NOT NULL,
  `junk_time` int(11) NOT NULL,
  `junk_zid` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `remote_ip` varchar(45) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_zid` (`zid`),
  KEY `comment_article_id` (`article_id`),
  FULLTEXT KEY `comment_search` (`subject`,`body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_edit`
--

DROP TABLE IF EXISTS `comment_edit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_edit` (
  `comment_id` int(11) NOT NULL,
  `edit_time` int(11) NOT NULL,
  `body` text NOT NULL,
  `subject` varchar(100) NOT NULL,
  KEY `comment_edit_index` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_view`
--

DROP TABLE IF EXISTS `comment_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_view` (
  `article_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `comments_clean` int(11) NOT NULL DEFAULT '-1',
  `comments_total` int(11) NOT NULL DEFAULT '-1',
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`zid`),
  KEY `view_article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_vote`
--

DROP TABLE IF EXISTS `comment_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_vote` (
  `comment_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `reason` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL,
  `country_name` varchar(50) NOT NULL,
  PRIMARY KEY (`country_id`),
  UNIQUE KEY `country_id_UNIQUE` (`country_id`),
  UNIQUE KEY `country_code_unique` (`country_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `default_conf`
--

DROP TABLE IF EXISTS `default_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `default_conf` (
  `conf` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  UNIQUE KEY `default_conf_unique` (`conf`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drive_data`
--

DROP TABLE IF EXISTS `drive_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_data` (
  `hash` varchar(64) NOT NULL,
  `server_id` int(11) NOT NULL,
  `size` bigint(20) NOT NULL,
  PRIMARY KEY (`hash`),
  UNIQUE KEY `hash_UNIQUE` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drive_dir`
--

DROP TABLE IF EXISTS `drive_dir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_dir` (
  `dir_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`dir_id`),
  UNIQUE KEY `path_id_UNIQUE` (`dir_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drive_file`
--

DROP TABLE IF EXISTS `drive_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_file` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `file_id_UNIQUE` (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drive_link`
--

DROP TABLE IF EXISTS `drive_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_link` (
  `hash` varchar(64) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`hash`,`item_id`,`type_id`),
  UNIQUE KEY `drive_link_unique` (`hash`,`item_id`,`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drive_server`
--

DROP TABLE IF EXISTS `drive_server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_server` (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(50) NOT NULL,
  PRIMARY KEY (`server_id`),
  UNIQUE KEY `server_id_UNIQUE` (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drive_version`
--

DROP TABLE IF EXISTS `drive_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drive_version` (
  `file_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`file_id`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_challenge`
--

DROP TABLE IF EXISTS `email_challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_challenge` (
  `code` varchar(64) NOT NULL,
  `email` varchar(50) NOT NULL,
  `expires` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `code_UNIQUE` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feed`
--

DROP TABLE IF EXISTS `feed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feed` (
  `feed_id` int(11) NOT NULL,
  `copyright` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `time` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `uri` varchar(200) NOT NULL,
  PRIMARY KEY (`feed_id`),
  UNIQUE KEY `feed_slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feed_topic`
--

DROP TABLE IF EXISTS `feed_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feed_topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `feed_topic_slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feed_user`
--

DROP TABLE IF EXISTS `feed_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feed_user` (
  `zid` varchar(50) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `col` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`zid`,`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL,
  `original_width` int(11) NOT NULL,
  `original_height` int(11) NOT NULL,
  `original_url` varchar(250) NOT NULL,
  `parent_url` varchar(250) NOT NULL,
  `server` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `image_style`
--

DROP TABLE IF EXISTS `image_style`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_style` (
  `image_style_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`image_style_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ip`
--

DROP TABLE IF EXISTS `ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(45) NOT NULL,
  `country_id` int(11) NOT NULL,
  `latitude` int(11) NOT NULL,
  `longitude` int(11) NOT NULL,
  PRIMARY KEY (`ip_id`),
  UNIQUE KEY `ip_id_UNIQUE` (`ip_id`),
  UNIQUE KEY `remote_ip_UNIQUE` (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal`
--

DROP TABLE IF EXISTS `journal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal` (
  `journal_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `edit_time` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `topic` varchar(20) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`journal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_photo`
--

DROP TABLE IF EXISTS `journal_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_photo` (
  `journal_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `karma_log`
--

DROP TABLE IF EXISTS `karma_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `karma_log` (
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  KEY `karma_index` (`zid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `karma_type`
--

DROP TABLE IF EXISTS `karma_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `karma_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login` (
  `zid` varchar(50) NOT NULL,
  `login_key` varchar(64) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `ip_id` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `os_id` int(11) NOT NULL,
  PRIMARY KEY (`login_key`,`zid`),
  UNIQUE KEY `login_unique` (`zid`,`login_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `body` text NOT NULL,
  `in_reply_to` varchar(250) NOT NULL,
  `location` varchar(250) NOT NULL,
  `mail_from` varchar(250) NOT NULL,
  `message_id` varchar(250) NOT NULL,
  `received_time` int(11) NOT NULL DEFAULT '0',
  `rcpt_to` varchar(250) NOT NULL,
  `reply_to` varchar(250) NOT NULL,
  `size` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(250) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`mail_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mail_dir`
--

DROP TABLE IF EXISTS `mail_dir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_dir` (
  `dir_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`dir_id`),
  UNIQUE KEY `slug_unique` (`zid`,`slug`),
  KEY `zid_index` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `notification_index` (`zid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `slug` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `photo_id` int(11) NOT NULL,
  `aspect_width` int(11) NOT NULL,
  `aspect_height` int(11) NOT NULL,
  `has_medium` int(11) NOT NULL,
  `has_large` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `original_name` varchar(250) NOT NULL,
  `original_width` int(11) NOT NULL,
  `original_height` int(11) NOT NULL,
  `server` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`photo_id`),
  UNIQUE KEY `short_id_UNIQUE` (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe`
--

DROP TABLE IF EXISTS `pipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe` (
  `pipe_id` int(11) NOT NULL,
  `author_zid` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `closed` tinyint(4) NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `edit_zid` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`pipe_id`),
  KEY `pipe_time` (`time`),
  FULLTEXT KEY `pipe_search` (`body`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe_vote`
--

DROP TABLE IF EXISTS `pipe_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe_vote` (
  `pipe_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`pipe_id`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll`
--

DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `poll_id` int(11) NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `promoted` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `question` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `type_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`poll_id`),
  KEY `poll_time` (`publish_time`),
  FULLTEXT KEY `poll_search` (`question`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_answer`
--

DROP TABLE IF EXISTS `poll_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_answer` (
  `answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`answer_id`),
  UNIQUE KEY `aid_UNIQUE` (`answer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_type`
--

DROP TABLE IF EXISTS `poll_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_type` varchar(50) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_vote`
--

DROP TABLE IF EXISTS `poll_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_vote` (
  `poll_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reader_topic`
--

DROP TABLE IF EXISTS `reader_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reader_topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `topic_id_UNIQUE` (`topic_id`),
  UNIQUE KEY `slug_UNIQUE` (`zid`,`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reader_user`
--

DROP TABLE IF EXISTS `reader_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reader_user` (
  `zid` varchar(64) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`zid`,`feed_id`),
  UNIQUE KEY `reader_user_feed_unique` (`zid`,`feed_id`),
  UNIQUE KEY `reader_user_slug_unique` (`zid`,`slug`),
  UNIQUE KEY `reader_user_name_unique` (`zid`,`name`),
  KEY `reader_user_zid` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `screenshot`
--

DROP TABLE IF EXISTS `screenshot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `screenshot` (
  `short_id` int(11) NOT NULL,
  `screenshot_id` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `hash` varchar(64) NOT NULL,
  `height` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(3) NOT NULL,
  `width` int(11) NOT NULL,
  PRIMARY KEY (`short_id`),
  UNIQUE KEY `short_id_UNIQUE` (`short_id`),
  UNIQUE KEY `screenshot_id_UNIQUE` (`screenshot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `server_conf`
--

DROP TABLE IF EXISTS `server_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server_conf` (
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `short`
--

DROP TABLE IF EXISTS `short`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `short` (
  `short_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`short_id`),
  UNIQUE KEY `short_id_UNIQUE` (`short_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `short_view`
--

DROP TABLE IF EXISTS `short_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `short_view` (
  `view_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `short_id` int(11) NOT NULL,
  `agent` varchar(250) NOT NULL,
  `referer` varchar(250) NOT NULL,
  `remote_ip` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`view_id`),
  UNIQUE KEY `view_id_UNIQUE` (`view_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_cart`
--

DROP TABLE IF EXISTS `store_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_cart` (
  `customer_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_category`
--

DROP TABLE IF EXISTS `store_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_description` text NOT NULL,
  `category_icon` varchar(20) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_feature`
--

DROP TABLE IF EXISTS `store_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_feature` (
  `feature_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `feature_description` text NOT NULL,
  `feature_name` varchar(50) NOT NULL,
  PRIMARY KEY (`feature_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_image`
--

DROP TABLE IF EXISTS `store_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_image` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_item`
--

DROP TABLE IF EXISTS `store_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `featured` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `shipping` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `features` varchar(50) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_item_feature`
--

DROP TABLE IF EXISTS `store_item_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_item_feature` (
  `feature_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `store_item_image`
--

DROP TABLE IF EXISTS `store_item_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_item_image` (
  `image_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `story`
--

DROP TABLE IF EXISTS `story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story` (
  `story_id` int(11) NOT NULL,
  `author_zid` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `comments_clean` int(11) NOT NULL,
  `comments_total` int(11) NOT NULL,
  `edit_time` int(11) NOT NULL,
  `edit_zid` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `image_id` int(11) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `pipe_id` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `tweet_id` bigint(20) NOT NULL,
  PRIMARY KEY (`story_id`),
  KEY `story_publish_time` (`publish_time`),
  FULLTEXT KEY `story_search` (`title`,`body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `story_edit`
--

DROP TABLE IF EXISTS `story_edit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_edit` (
  `story_id` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `edit_time` int(11) NOT NULL,
  `edit_zid` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `image_id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `topic_id` int(11) NOT NULL,
  UNIQUE KEY `story_edit_index` (`edit_time`,`story_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stream_main`
--

DROP TABLE IF EXISTS `stream_main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stream_main` (
  `article_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`article_id`),
  KEY `stream_time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stream_user`
--

DROP TABLE IF EXISTS `stream_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stream_user` (
  `zid` varchar(50) NOT NULL,
  `article_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`zid`,`article_id`),
  KEY `stream_time` (`zid`,`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stream_vote`
--

DROP TABLE IF EXISTS `stream_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stream_vote` (
  `zid` varchar(50) NOT NULL,
  `article_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`zid`,`article_id`),
  KEY `stream_vote_card_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(20) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_UNIQUE` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `thumb`
--

DROP TABLE IF EXISTS `thumb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `thumb` (
  `thumb_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `low_res` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`thumb_id`),
  UNIQUE KEY `thumb_id_UNIQUE` (`thumb_id`),
  UNIQUE KEY `hash_UNIQUE` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tmp_image`
--

DROP TABLE IF EXISTS `tmp_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_image` (
  `tmp_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) NOT NULL,
  `original_width` int(11) NOT NULL,
  `original_height` int(11) NOT NULL,
  `original_url` varchar(250) NOT NULL,
  `parent_url` varchar(250) NOT NULL,
  `server` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`tmp_image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(20) NOT NULL,
  `promoted` tinyint(4) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `topic` varchar(50) NOT NULL,
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `zid_UNIQUE` (`zid`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_conf`
--

DROP TABLE IF EXISTS `user_conf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_conf` (
  `zid` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  UNIQUE KEY `user_conf_unique` (`zid`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-28 23:13:02
