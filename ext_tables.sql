#
# Table structure for table 'tx_ibkblog_domain_model_blog'
#
CREATE TABLE tx_ibkblog_domain_model_blog (

	name varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	titel varchar(255) DEFAULT '' NOT NULL,
	kurzfassung text,
	inhalt text,
	datum datetime DEFAULT NULL,
	link varchar(255) DEFAULT '' NOT NULL,
	kategorie int(11) unsigned DEFAULT '0',
	tags int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_ibkblog_domain_model_kategorie'
#
CREATE TABLE tx_ibkblog_domain_model_kategorie (

	name varchar(255) DEFAULT '' NOT NULL,

);

#
# Table structure for table 'tx_ibkblog_domain_model_tag'
#
CREATE TABLE tx_ibkblog_domain_model_tag (

	name varchar(255) DEFAULT '' NOT NULL,

);

#
# Table structure for table 'tx_ibkblog_blog_tag_mm'
#
CREATE TABLE tx_ibkblog_blog_tag_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
