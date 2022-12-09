ALTER TABLE `tasks_comments` ADD `tasks_priority_id` INT NULL AFTER `tasks_status_id` ,
ADD `due_date` DATE NULL AFTER `tasks_priority_id` ,
ADD `worked_hours` FLOAT NULL AFTER `due_date`;
ALTER TABLE `tasks_comments` ADD INDEX `fk_tasks_comments_priority` ( `tasks_priority_id` ); 

ALTER TABLE `tasks_comments` ADD FOREIGN KEY ( `tasks_priority_id` ) REFERENCES `tasks_priority` (
`id`
) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE `user_reports` ADD `sort_order` INT NULL;

--
-- Table structure for table 'tickets_reports'
--

CREATE TABLE tickets_reports (
  id int(11) NOT NULL auto_increment,
  users_id int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  display_on_home tinyint(1) default NULL,
  projects_id text,
  projects_type_id text,
  projects_status_id text,
  departments_id text,
  tickets_types_id text,
  tickets_status_id text,
  sort_order int(11) default NULL,
  PRIMARY KEY  (id),
  KEY users_id (users_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets_reports`
--
ALTER TABLE `tickets_reports`
  ADD CONSTRAINT tickets_reports_ibfk_1 FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE;
