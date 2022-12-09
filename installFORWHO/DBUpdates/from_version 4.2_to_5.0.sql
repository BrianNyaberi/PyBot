
--
-- Table structure for table 'departments'
--

CREATE TABLE departments (
  id int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  users_id int(11) NOT NULL,
  sort_order int(11) default NULL,
  active tinyint(1) default NULL,
  PRIMARY KEY  (id),
  KEY fk_departments_users (users_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'tickets'
--

CREATE TABLE tickets (
  id int(11) NOT NULL auto_increment,
  departments_id int(11) default NULL,
  tickets_types_id int(11) default NULL,
  tickets_status_id int(11) default NULL,
  `name` varchar(255) NOT NULL,
  description text,
  users_id int(11) NOT NULL,
  projects_id int(11) NOT NULL,
  created_at datetime default NULL,
  PRIMARY KEY  (id),
  KEY fk_tickets_users (users_id),
  KEY fk_tickets_tickets_status (tickets_status_id),
  KEY fk_tickets_tickets_types (tickets_types_id),
  KEY fk_tickets_projects (projects_id),
  KEY fk_tickets_departments (departments_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'tickets_comments'
--

CREATE TABLE tickets_comments (
  id int(11) NOT NULL auto_increment,
  description text,
  created_at timestamp NULL default NULL,
  tickets_id int(11) NOT NULL,
  users_id int(11) default NULL,
  tickets_status_id int(11) default NULL,
  PRIMARY KEY  (id),
  KEY fk_tickets_comments_tickets (tickets_id),
  KEY fk_tickets_comments_users (users_id),
  KEY k_tickets_comments_status (tickets_status_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'tickets_status'
--

CREATE TABLE tickets_status (
  id int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `group` varchar(64) default NULL,
  sort_order int(11) default NULL,
  default_value int(11) default NULL,
  active varchar(1) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'tickets_types'
--

CREATE TABLE tickets_types (
  id int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  sort_order int(11) default NULL,
  active varchar(1) default NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT fk_departments_users FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT fk_tickets_tickets_status FOREIGN KEY (tickets_status_id) REFERENCES tickets_status (id) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT fk_tickets_tickets_types FOREIGN KEY (tickets_types_id) REFERENCES tickets_types (id) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT fk_tickets_users FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT tickets_ibfk_1 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT tickets_ibfk_2 FOREIGN KEY (departments_id) REFERENCES departments (id) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `tickets_comments`
--
ALTER TABLE `tickets_comments`
  ADD CONSTRAINT fk_tickets_comments_tickets FOREIGN KEY (tickets_id) REFERENCES tickets (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_tickets_comments_users FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT tickets_comments_ibfk_1 FOREIGN KEY (tickets_status_id) REFERENCES tickets_status (id) ON DELETE SET NULL ON UPDATE SET NULL;



-- -----------------------------------------------------
-- ALTER TABLE `tasks`
-- -----------------------------------------------------
ALTER TABLE `tasks` ADD `closed_date` DATE NULL; 

ALTER TABLE `tasks` ADD `tickets_id` INT NULL;

ALTER TABLE `tasks` ADD INDEX `fk_tasks_tickets` ( `tickets_id` );

ALTER TABLE `tasks` ADD FOREIGN KEY ( `tickets_id` ) REFERENCES `tickets` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;


-- -----------------------------------------------------
-- ALTER TABLE `users_groups`
-- -----------------------------------------------------
ALTER TABLE `users_groups` ADD `allow_manage_tasks` TINYINT( 1 ) NULL AFTER `allow_manage_projects` ,
ADD `allow_manage_tickets` TINYINT( 1 ) NULL AFTER `allow_manage_tasks`;

UPDATE `users_groups` SET `allow_manage_tasks` =1;

 -- -----------------------------------------------------
-- ALTER TABLE `user_reports`
-- -----------------------------------------------------
ALTER TABLE `user_reports` ADD `closed_from` DATE NULL ,
ADD `closed_to` DATE NULL; 


--
-- Dumping data for table `tickets_status`
--

INSERT INTO `tickets_status` VALUES(1, 'New', 'open', 0, 1, '1');
INSERT INTO `tickets_status` VALUES(2, 'Open', 'open', 1, NULL, '1');
INSERT INTO `tickets_status` VALUES(3, 'Waiting Assessment', 'open', 3, NULL, '1');
INSERT INTO `tickets_status` VALUES(4, 'Re-opened', 'open', 2, NULL, '1');
INSERT INTO `tickets_status` VALUES(5, 'Resolved', 'closed', 4, NULL, '1');
INSERT INTO `tickets_status` VALUES(6, 'Canceled', 'closed', 7, NULL, '1');
INSERT INTO `tickets_status` VALUES(7, 'Fixed', 'closed', 6, NULL, '1');
INSERT INTO `tickets_status` VALUES(8, 'Closed', 'closed', 5, NULL, '1');

--
-- Dumping data for table `tickets_types`
--

INSERT INTO `tickets_types` VALUES(1, 'Report a Bug', 1, '1');
INSERT INTO `tickets_types` VALUES(2, 'Request a Change', 0, '1');
INSERT INTO `tickets_types` VALUES(3, 'Raise an Issue', 3, '1');
INSERT INTO `tickets_types` VALUES(4, 'Ask a Question', 2, '1');

--
-- Table structure for table 'events'
--

CREATE TABLE `events` (
  event_id int(11) NOT NULL auto_increment,
  event_name varchar(127) NOT NULL,
  start_date datetime NOT NULL,
  end_date datetime NOT NULL,
  details text NOT NULL,
  users_id int(11) NOT NULL,
  PRIMARY KEY  (event_id),
  KEY fk_events_users (users_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT events_ibfk_1 FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE;