ALTER TABLE  `users` ADD  `skin` VARCHAR( 64 ) NULL;

CREATE TABLE configuration (
  id int(11) NOT NULL auto_increment,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE projects_reports (
  id int(11) NOT NULL auto_increment,
  users_id int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  display_on_home tinyint(1) default NULL,
  projects_id text,
  projects_type_id text,
  projects_groups_id text,
  projects_status_id text,
  in_team int(11) default NULL,
  sort_order int(11) default NULL,
  display_in_menu tinyint(1) default NULL,
  visible_on_home tinyint(1) default NULL,
  PRIMARY KEY  (id),
  KEY users_id (users_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `projects_reports`
  ADD CONSTRAINT `projects_reports_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;