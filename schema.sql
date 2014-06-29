-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (x86_64)
--
-- Host: yuzu.zenbi.net    Database: pipedot
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.14.04.1

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
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `archive` int(11) NOT NULL,
  `body` text NOT NULL,
  `full_body` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `time` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`article_id`)
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
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`cid`),
  KEY `comment_zid` (`zid`),
  FULLTEXT KEY `comment_search` (`subject`,`comment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment_vote`
--

DROP TABLE IF EXISTS `comment_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_vote` (
  `cid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `rid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`cid`,`zid`)
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
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `editor` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `ctitle` varchar(100) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `closed` tinyint(4) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `story` text NOT NULL,
  PRIMARY KEY (`pid`),
  FULLTEXT KEY `pipe_search` (`story`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe_history`
--

DROP TABLE IF EXISTS `pipe_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe_history` (
  `pid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`pid`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pipe_vote`
--

DROP TABLE IF EXISTS `pipe_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipe_vote` (
  `pid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`pid`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_answer`
--

DROP TABLE IF EXISTS `poll_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_answer` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `answer` varchar(200) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_history`
--

DROP TABLE IF EXISTS `poll_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_history` (
  `qid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`qid`,`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `poll_question`
--

DROP TABLE IF EXISTS `poll_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_question` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `question` varchar(200) NOT NULL,
  PRIMARY KEY (`qid`),
  FULLTEXT KEY `poll_search` (`question`)
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
  `qid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `aid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  KEY `poll_vote_index` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reason`
--

DROP TABLE IF EXISTS `reason`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reason` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(20) NOT NULL,
  `value` int(11) NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`rid`)
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
-- Table structure for table `story`
--

DROP TABLE IF EXISTS `story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `ctitle` varchar(100) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `story` text NOT NULL,
  `image_id` int(11) NOT NULL,
  `tweet_id` bigint(20) NOT NULL,
  `zid` varchar(50) NOT NULL,
  PRIMARY KEY (`sid`),
  FULLTEXT KEY `story_search` (`title`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `story_history`
--

DROP TABLE IF EXISTS `story_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_history` (
  `sid` int(11) NOT NULL,
  `zid` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  PRIMARY KEY (`sid`,`zid`)
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
  `topic` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `promoted` tinyint(4) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `luser` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `editor` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL,
  `email_verified` tinyint(4) NOT NULL,
  `expand_threshold` int(11) NOT NULL DEFAULT '1',
  `hide_threshold` int(11) NOT NULL DEFAULT '0',
  `javascript_enabled` tinyint(4) NOT NULL DEFAULT '1',
  `joined` int(11) NOT NULL,
  `karma` int(11) NOT NULL DEFAULT '0',
  `list_enabled` tinyint(4) NOT NULL,
  `real_name` varchar(50) NOT NULL,
  `time_zone` varchar(50) NOT NULL DEFAULT 'UTC',
  PRIMARY KEY (`uid`)
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

-- Dump completed on 2014-06-29  1:07:29
