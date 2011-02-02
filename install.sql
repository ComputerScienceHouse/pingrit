CREATE TABLE IF NOT EXISTS `ips` (
  `thirdo` tinyint(3) unsigned NOT NULL COMMENT 'x in 129.21.x.y',
  `fourtho` tinyint(3) unsigned NOT NULL COMMENT 'y in 129.21.x.y',
  `exists` set('DNE','REG','PING') character set ascii NOT NULL COMMENT 'DNE=does not exist, REG=registered, PING=responds',
  `web` smallint(5) unsigned NOT NULL COMMENT 'HTTP response code or 0',
  `hostname` varchar(25) character set ascii NOT NULL COMMENT 'x in x.rit.edu',
  `domain` varchar(25) character set ascii NOT NULL COMMENT 'rit.edu in x.rit.edu'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table of ips';

CREATE TABLE `pingrit`.`stats` (
`date` DATETIME NOT NULL COMMENT 'The date the scan was last ran',
`tried` SMALLINT UNSIGNED NOT NULL COMMENT 'Number of IPs tried',
`ping` SMALLINT UNSIGNED NOT NULL COMMENT 'Number of IPs that responded',
`reg` SMALLINT UNSIGNED NOT NULL COMMENT 'Number of IPs that are registered',
`dne` SMALLINT UNSIGNED NOT NULL COMMENT 'Number of IPs that did not respond',
`web` SMALLINT UNSIGNED NOT NULL COMMENT 'Number of web servers',
PRIMARY KEY ( `date` )
) ENGINE = MYISAM ;
