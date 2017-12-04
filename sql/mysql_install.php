<?php


#
# Table structure for table `faq_categories`
#

$_SQL[] = "CREATE TABLE {$_TABLES['faq_categories']} (
  catID int(4) NOT NULL auto_increment,
  name char(50) NOT NULL default '',
  description char(125) NOT NULL default '',
  total int(11) NOT NULL default '0',
  PRIMARY KEY  (catID),
  UNIQUE KEY catID (catID)
) TYPE=MyISAM COMMENT='Part of FAQ Manager v2'";

#
# Table structure for table `faq_topics`
#

$_SQL[] = "CREATE TABLE {$_TABLES['faq_topics']} (
  topicID int(4) NOT NULL auto_increment,
  catID int(4) NOT NULL default '0',
  question varchar(75) NOT NULL default '',
  answer text NOT NULL,
  keywords varchar(125) NOT NULL default '',
  PRIMARY KEY  (topicID),
  UNIQUE KEY topicID (topicID),
  FULLTEXT KEY `keywords` (keywords),
  FULLTEXT KEY `answer` (answer)
) TYPE=MyISAM COMMENT='Part of FAQ Manager v2'";


?>