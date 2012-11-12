-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Oct 24, 2012 at 12:34 AM
-- Server version: 5.0.77
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `woorus_pup`
--
--CREATE DATABASE `woorus_pup` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
--USE `woorus_pup`;

-- --------------------------------------------------------

--
-- Table structure for table `banned_words`
--

CREATE TABLE IF NOT EXISTS `banned_words` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `word` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=609 ;

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_blocker` int(10) unsigned NOT NULL,
  `user_blockee` int(10) unsigned NOT NULL,
  `update_time` datetime NOT NULL,
  `block_reason` varchar(254) default NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `city_name` varchar(255) NOT NULL,
  `state_id` int(10) unsigned NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=227952 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_contacter` int(10) unsigned NOT NULL,
  `user_contactee` int(10) unsigned NOT NULL,
  `update_time` datetime NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `caller_id` int(10) unsigned NOT NULL,
  `callee_id` int(10) unsigned NOT NULL,
  `update_time` datetime NOT NULL,
  `call_state` enum('not_received','received','accepted','accepted_recv','rejected','rejected_recv','missed','missed_recv','canceled') default NULL,
  `call_ended` tinyint(1) NOT NULL default '0',
  `distance` int(10) unsigned NOT NULL,
  `call_time` time NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=409 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `country_abbreviation` varchar(10) NOT NULL,
  `country_name` varchar(225) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=225 ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE IF NOT EXISTS `failed_logins` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_email` varchar(255) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `flagged_images`
--

CREATE TABLE IF NOT EXISTS `flagged_images` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` enum('tile','profile') NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  `flag_reason` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `guestlist`
--

CREATE TABLE IF NOT EXISTS `guestlist` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email_address` varchar(255) NOT NULL,
  `visual_email_address` varchar(255) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE IF NOT EXISTS `interests` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `interest_name` varchar(60) NOT NULL,
  `category` varchar(100) default NULL,
  `facebook_id` bigint(10) unsigned default NULL,
  `facebook_category` varchar(100) default NULL,
  `update_time` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=519 ;

-- --------------------------------------------------------

--
-- Table structure for table `invite_codes`
--

CREATE TABLE IF NOT EXISTS `invite_codes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `access_code` int(10) unsigned NOT NULL,
  `max_use` smallint(5) unsigned NOT NULL,
  `num_used` smallint(5) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `admin_user` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE IF NOT EXISTS `invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `invite_email` varchar(254) NOT NULL,
  `invite_message` text NOT NULL,
  `code_id` int(10) unsigned NOT NULL,
  `update_time` datetime NOT NULL,
  `invite_success` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_mailer` int(10) unsigned NOT NULL,
  `user_mailee` int(10) unsigned NOT NULL,
  `message_text` text NOT NULL,
  `sent_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `message_read` tinyint(1) NOT NULL,
  `message_deleted_by_mailee` tinyint(1) NOT NULL,
  `message_deleted_by_mailer` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

--
-- Table structure for table `mosaic_wall`
--

CREATE TABLE IF NOT EXISTS `mosaic_wall` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `tile_placement` tinyint(3) unsigned NOT NULL,
  `tile_id` int(10) unsigned NOT NULL,
  `interest_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2472475 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile_picture`
--

CREATE TABLE IF NOT EXISTS `profile_picture` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `update_time` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `profile_filename_large` varchar(250) NOT NULL,
  `profile_filename_small` varchar(250) default NULL,
  `picture_flagged` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `m_username` varchar(30) character set ascii collate ascii_bin NOT NULL,
  `m_identity` varchar(64) NOT NULL,
  `m_updatetime` datetime NOT NULL,
  PRIMARY KEY  (`m_username`),
  UNIQUE KEY `registrations_updatetime` (`m_updatetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `interest_notify` enum('Y','N') NOT NULL default 'Y',
  `message_notify` enum('Y','N') NOT NULL default 'Y',
  `contact_notify` enum('Y','N') NOT NULL default 'Y',
  `missed_call_notify` enum('Y','N') NOT NULL default 'Y',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=167 ;

-- --------------------------------------------------------

--
-- Table structure for table `tiles`
--

CREATE TABLE IF NOT EXISTS `tiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `interest_id` int(10) unsigned NOT NULL,
  `tile_filename` varchar(250) NOT NULL,
  `update_time` datetime NOT NULL,
  `picture_flagged` tinyint(1) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `facebook_id` bigint(20) default NULL,
  `sponsored` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=674 ;

-- --------------------------------------------------------

--
-- Table structure for table `us_states`
--

CREATE TABLE IF NOT EXISTS `us_states` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `state_abbreviation` varchar(2) NOT NULL,
  `state_name` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
--

CREATE TABLE IF NOT EXISTS `user_interests` (
  `id` bigint(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `interest_id` bigint(10) unsigned NOT NULL,
  `tile_id` bigint(20) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1698 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `last_login_time` datetime NOT NULL,
  `user_active` datetime NOT NULL,
  `session_set` tinyint(1) NOT NULL,
  `on_call` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `email_address` varchar(254) NOT NULL,
  `visual_email_address` varchar(254) NOT NULL,
  `temp_email_address` varchar(254) default NULL,
  `password` varchar(32) default NULL,
  `password_token` varchar(10) default NULL,
  `gender` enum('M','F') NOT NULL,
  `birthday` date NOT NULL,
  `user_city_id` int(10) unsigned NOT NULL,
  `social_status` enum('a','b','c','d','e') NOT NULL,
  `block_status` enum('a','b','c','d') NOT NULL default 'a',
  `join_date` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  `email_token` varchar(10) default NULL,
  `email_verified` tinyint(1) NOT NULL default '0',
  `password_set` tinyint(1) NOT NULL,
  `user_info_set` tinyint(1) NOT NULL,
  `facebook_id` bigint(20) default NULL,
  `active_user` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;
