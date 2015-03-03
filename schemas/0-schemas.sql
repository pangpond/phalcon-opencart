CREATE TABLE IF NOT EXISTS `academy` (
  `academy_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_id` mediumint(5) unsigned DEFAULT NULL,
  `district_id` mediumint(5) unsigned DEFAULT NULL,
  `subdistrict_id` mediumint(5) unsigned DEFAULT NULL,
  `zipcode` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `smis_code` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ministry_code` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `division_id` mediumint(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_id` mediumint(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`academy_id`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `academy_meta` (
  `academy_meta_id` mediumint(20) unsigned NOT NULL AUTO_INCREMENT,
  `academy_id` int(11) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`academy_meta_id`),
  KEY `academy_id` (`academy_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `area` (
  `area_id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8_unicode_ci,
  `province_id` mediumint(5) unsigned NOT NULL,
  `district_id` mediumint(5) unsigned NOT NULL,
  `subdistrict_id` mediumint(5) unsigned NOT NULL,
  `zipcode` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`area_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `area_director` (
  `area_id` mediumint(5) unsigned NOT NULL,
  `people_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `people_id` (`people_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `area_province` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `area_id` mediumint(5) unsigned NOT NULL,
  `province_id` mediumint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `area_meta` (
  `area_meta_id` mediumint(20) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` mediumint(20) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`area_meta_id`),
  KEY `area_id` (`area_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `divisions` (
  `division_id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `division_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `division_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `division_under` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`division_id`),
  KEY `division_code` (`division_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `people` (
  `people_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `citizenid` varchar(13) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bloodgroup` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthdate` date COLLATE utf8_unicode_ci DEFAULT '0000-00-00',
  `nationality` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_id` mediumint(5) unsigned NOT NULL,
  `district_id` mediumint(5) unsigned NOT NULL,
  `subdistrict_id` mediumint(5) unsigned NOT NULL,
  `zipcode` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `area_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registered` datetime COLLATE utf8_unicode_ci DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) COLLATE utf8_unicode_ci DEFAULT '1',
  PRIMARY KEY (`people_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `people_meta` (
  `people_meta_id` mediumint(20) unsigned NOT NULL AUTO_INCREMENT,
  `people_id` int(11) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`people_meta_id`),
  KEY `people_id` (`people_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `people_academy` (
  `academy_id` mediumint(5) unsigned NOT NULL,
  `prople_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `prople_id` (`prople_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


CREATE TABLE IF NOT EXISTS `province_director` (
  `province_id` mediumint(5) unsigned NOT NULL,
  `prople_id` int(11) unsigned NOT NULL,
  UNIQUE KEY `prople_id` (`prople_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `area_director` (
  `area_id` mediumint(5) unsigned NOT NULL,
  `prople_id` mediumint(5) unsigned NOT NULL,
  UNIQUE KEY `prople_id` (`prople_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `users_meta` (
  `user_meta_id` mediumint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`user_meta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `district` (
`district_id` int(5) NOT NULL,
  `district_code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `district_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `district_name_en` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `geo_id` int(5) NOT NULL DEFAULT '0',
  `province_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`district_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `geography` (
  `geo_id` int(5) NOT NULL AUTO_INCREMENT,
  `geo_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`geo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `province` (
`province_id` int(5) NOT NULL,
  `province_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `province_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `province_name_en` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `geo_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`province_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `settings` (
`setting_id` int(10) unsigned NOT NULL,
  `setting_key` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `setting_value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `subdistrict` (
  `subdistrict_id` int(5) NOT NULL AUTO_INCREMENT,
  `subdistrict_code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `subdistrict_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `geo_id` int(5) NOT NULL DEFAULT '0',
  `province_id` int(5) NOT NULL DEFAULT '0',
  `district_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`subdistrict_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `members_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_id` tinyint(4) NOT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `members` tinyint(4) NOT NULL,
  `completed` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
