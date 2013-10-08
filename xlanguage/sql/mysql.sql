#
# Table structure for table `language`
#

CREATE TABLE `xlanguage_base` (
  `lang_id` 		int(8) 			unsigned NOT NULL auto_increment,
  `weight` 			smallint(4) 	NOT NULL default '1',
  `lang_name` 		varchar(255) 	NOT NULL default '',
  `lang_desc` 		varchar(255) 	NOT NULL default '',
  `lang_code` 		varchar(255) 	NOT NULL default '',
  `lang_charset`	varchar(255) 	NOT NULL default '',
  `lang_image` 		varchar(255) 	NOT NULL default '',
  PRIMARY KEY  		(`lang_id`),
  KEY `lang_name` 	(`lang_name`)
) ENGINE=MyISAM;

CREATE TABLE `xlanguage_ext` (
  `lang_id` 		int(8) 			unsigned NOT NULL auto_increment,
  `weight` 			smallint(4) 	NOT NULL default '1',
  `lang_name` 		varchar(255) 	NOT NULL default '',
  `lang_desc` 		varchar(255) 	NOT NULL default '',
  `lang_code` 		varchar(255) 	NOT NULL default '',
  `lang_charset` 	varchar(255) 	NOT NULL default '',
  `lang_image` 		varchar(255) 	NOT NULL default '',
  `lang_base` 		varchar(255) 	NOT NULL default '',
  PRIMARY KEY  		(`lang_id`),
  KEY `lang_name` 	(`lang_name`),
  KEY `lang_base` 	(`lang_base`)
) ENGINE=MyISAM;