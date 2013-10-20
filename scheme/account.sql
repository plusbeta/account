-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- ホスト: mysql64.heteml.jp
-- 生成時間: 2012 年 1 月 13 日 18:27
-- サーバのバージョン: 5.0.82
-- PHP のバージョン: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- データベース: `_account`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL,
  `client_people_id` int(11) default NULL,
  `member_id` int(11) NOT NULL,
  `sub_member_id` int(11) default NULL,
  `bank_id` int(11) default NULL,
  `account_kind` smallint(6) default NULL,
  `account_no` int(10) default '0',
  `bill_code` varchar(30) default NULL,
  `name` varchar(255) NOT NULL,
  `comment` varchar(255) default NULL,
  `contract_price` int(11) NOT NULL,
  `condition` varchar(255) default NULL,
  `terms1` varchar(512) default NULL,
  `terms2` varchar(512) default NULL,
  `terms3` varchar(512) default NULL,
  `terms4` varchar(512) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  `temporary` tinyint(1) default '0',
  `only_estimate` tinyint(1) default '0',
  `estimate_flag` tinyint(1) default '0',
  `bill_flag` tinyint(1) default '0',
  `delivery_flag` tinyint(1) default '0',
  `receive_flag` tinyint(1) default '0',
  `estimate_limit` date default NULL,
  `estimate_date` date default NULL,
  `bill_date` date default NULL,
  `delivery_date` date default NULL,
  `receive_date` date default NULL,
  `disp_member` tinyint(1) NOT NULL default '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL auto_increment,
  `setting_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `kind` tinyint(2) NOT NULL,
  `number` varchar(12) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(10) default NULL,
  `name` varchar(255) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `fax` varchar(12) default NULL,
  `email` varchar(255) default NULL,
  `type` tinyint(4) NOT NULL,
  `remark` varchar(512) default NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `client_people`
--

CREATE TABLE `client_people` (
  `id` int(11) NOT NULL auto_increment,
  `client_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `kana` varchar(50) NOT NULL,
  `devision` varchar(50) default NULL,
  `title` varchar(50) default NULL,
  `tel` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `remark` varchar(512) default NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `contractors`
--

CREATE TABLE `contractors` (
  `id` int(11) NOT NULL auto_increment,
  `account_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL auto_increment,
  `account_id` int(11) NOT NULL,
  `order` tinyint(2) default NULL,
  `name` varchar(128) default NULL,
  `content` varchar(128) NOT NULL,
  `unit_price` int(11) default NULL,
  `number` float(10,1) default NULL,
  `amount` int(11) default NULL,
  `remark` varchar(512) default NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `valid` tinyint(1) NOT NULL default '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `remarks`
--

CREATE TABLE `remarks` (
  `id` int(11) NOT NULL auto_increment,
  `setting_id` int(11) NOT NULL,
  `sentence` varchar(512) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL auto_increment,
  `company_name` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `fax` varchar(12) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `letter_title` varchar(50) NOT NULL,
  `estimate_prefix` varchar(20) default NULL,
  `estimate_title` varchar(20) NOT NULL,
  `estimate_sentense` varchar(255) NOT NULL,
  `estimate_limit` int(4) NOT NULL default '60',
  `receive_limit` int(4) NOT NULL default '60',
  `condition` varchar(255) default NULL,
  `terms1` varchar(512) default NULL,
  `terms2` varchar(512) default NULL,
  `terms3` varchar(512) default NULL,
  `terms4` varchar(512) default NULL,
  `bill_title` varchar(20) default NULL,
  `bill_sentense` varchar(255) default NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
