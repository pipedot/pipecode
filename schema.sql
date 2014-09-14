-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: yuzu.zenbi.net    Database: pipedot
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
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `archive` int(11) NOT NULL,
  `body` text NOT NULL,
  `image_id` int(11) NOT NULL,
  `link_subject` varchar(200) NOT NULL,
  `link_url` varchar(200) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`card_id`)
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
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `card_tags_index` (`card_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `card_vote`
--

DROP TABLE IF EXISTS `card_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_vote` (
  `card_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  UNIQUE KEY `card_vote_index` (`card_id`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `comment_id` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `parent_id` varchar(64) NOT NULL,
  `root_id` varchar(64) NOT NULL,
  `short_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_zid` (`zid`),
  KEY `comment_root_id` (`root_id`),
  KEY `comment_short_id` (`short_id`),
  FULLTEXT KEY `comment_search` (`subject`,`body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_vote`
--

DROP TABLE IF EXISTS `comment_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_vote` (
  `comment_id` varchar(64) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `reason` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`,`zid`)
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
-- Table structure for table `email_challenge`
--

DROP TABLE IF EXISTS `email_challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_challenge` (
  `challenge` varchar(64) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `expires` int(11) NOT NULL,
  PRIMARY KEY (`challenge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feed`
--

DROP TABLE IF EXISTS `feed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feed` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uri` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feed_item`
--

DROP TABLE IF EXISTS `feed_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feed_item` (
  `fid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  `time` varchar(45) NOT NULL
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
  `fid` int(11) NOT NULL,
  `col` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`fid`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gravatar`
--

DROP TABLE IF EXISTS `gravatar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gravatar` (
  `zid` varchar(50) NOT NULL,
  `last_view` int(11) NOT NULL,
  `last_sync` int(11) NOT NULL,
  PRIMARY KEY (`zid`)
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
-- Table structure for table `journal`
--

DROP TABLE IF EXISTS `journal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal` (
  `journal_id` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `edit_time` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `short_id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `topic` varchar(20) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`journal_id`),
  UNIQUE KEY `journal_id_UNIQUE` (`journal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_photo`
--

DROP TABLE IF EXISTS `journal_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_photo` (
  `journal_id` varchar(64) NOT NULL,
  `photo_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `journal_view`
--

DROP TABLE IF EXISTS `journal_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `journal_view` (
  `journal_id` varchar(64) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`journal_id`,`zid`)
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
-- Table structure for table `link`
--

DROP TABLE IF EXISTS `link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `link` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `time` int(11) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `list_log`
--

DROP TABLE IF EXISTS `list_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_log` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `id` varchar(100) NOT NULL,
  `reply` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`lid`)
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
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe`
--

DROP TABLE IF EXISTS `pipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe` (
  `pipe_id` varchar(64) NOT NULL,
  `author_zid` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `closed` tinyint(4) NOT NULL,
  `edit_zid` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `short_id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `tid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`pipe_id`),
  FULLTEXT KEY `pipe_search` (`body`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe_view`
--

DROP TABLE IF EXISTS `pipe_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe_view` (
  `pipe_id` varchar(64) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`pipe_id`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe_vote`
--

DROP TABLE IF EXISTS `pipe_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe_vote` (
  `pipe_id` varchar(64) NOT NULL,
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
  `poll_id` varchar(64) NOT NULL,
  `question` varchar(200) NOT NULL,
  `short_id` int(11) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `time` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`poll_id`),
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
  `answer_id` varchar(64) NOT NULL,
  `poll_id` varchar(64) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`answer_id`)
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
-- Table structure for table `poll_view`
--

DROP TABLE IF EXISTS `poll_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_view` (
  `poll_id` varchar(64) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`poll_id`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_vote`
--

DROP TABLE IF EXISTS `poll_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_vote` (
  `poll_id` varchar(64) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `answer_id` varchar(64) NOT NULL,
  `time` int(11) NOT NULL,
  `points` int(11) NOT NULL
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
  `type` varchar(20) NOT NULL,
  `item_id` varchar(64) NOT NULL,
  PRIMARY KEY (`short_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `short_view`
--

DROP TABLE IF EXISTS `short_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `short_view` (
  `short_id` int(11) NOT NULL,
  `agent` varchar(250) NOT NULL,
  `referer` varchar(250) NOT NULL,
  `remote_ip` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  KEY `short_view_index` (`short_id`)
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
  `story_id` varchar(64) NOT NULL,
  `author_zid` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `edit_time` int(11) NOT NULL,
  `edit_zid` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `image_id` int(11) NOT NULL,
  `pipe_id` varchar(64) NOT NULL,
  `publish_time` int(11) NOT NULL,
  `short_id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `tid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `tweet_id` bigint(20) NOT NULL,
  PRIMARY KEY (`story_id`),
  KEY `story_short_id` (`short_id`),
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
  `tid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  UNIQUE KEY `story_edit_index` (`edit_time`,`story_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `story_view`
--

DROP TABLE IF EXISTS `story_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_view` (
  `story_id` varchar(64) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`story_id`,`zid`)
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
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(20) NOT NULL,
  `promoted` tinyint(4) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `topic` varchar(50) NOT NULL,
  PRIMARY KEY (`tid`)
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

-- Dump completed on 2014-09-13 18:58:50
