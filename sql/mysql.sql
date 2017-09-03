#
# Table structure for table `language`
#

CREATE TABLE `xlanguage_base` (
  `lang_id`      INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weight`       SMALLINT(4)     NOT NULL DEFAULT '1',
  `lang_name`    VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_desc`    VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_code`    VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_charset` VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_image`   VARCHAR(255)    NOT NULL DEFAULT '',
  PRIMARY KEY (`lang_id`),
  KEY `lang_name`     (`lang_name`)
)
  ENGINE = MyISAM;

CREATE TABLE `xlanguage_ext` (
  `lang_id`      INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weight`       SMALLINT(4)     NOT NULL DEFAULT '1',
  `lang_name`    VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_desc`    VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_code`    VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_charset` VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_image`   VARCHAR(255)    NOT NULL DEFAULT '',
  `lang_base`    VARCHAR(255)    NOT NULL DEFAULT '',
  PRIMARY KEY (`lang_id`),
  KEY `lang_name`     (`lang_name`),
  KEY `lang_base`     (`lang_base`)
)
  ENGINE = MyISAM;
