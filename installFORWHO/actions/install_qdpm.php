<?php
/**
* WORK SMART
*/
?>
<?php
  
  require('../core/lib/PasswordHash.php');
  
  $hasher = new PasswordHash(11, false);
  
  $sql_file = 'install.sql';
  
  $server = $_POST['db_host'];
  $port = $_POST['db_port'];
  $username = $_POST['db_username'];
  $password = $_POST['db_password'];
  $database = $_POST['db_name'];
       
  tep_db_connect($server. (strlen($port)>0 ? ':' . $port : ''),$username, $password, $database);
             
  if (file_exists($sql_file)) {    
    $fd = fopen($sql_file, 'rb');
    $restore_query = fread($fd, filesize($sql_file));
    fclose($fd);    
  } else {
    echo 'SQL file does not exist: ' . $sql_file;
    exit();
  }
  
$configuration_query = "

INSERT IGNORE INTO configuration VALUES
('1','app_administrator_email','" . trim(addslashes($_POST['administrator_email'])). "'),
('2','app_administrator_password','" . trim(addslashes($hasher->HashPassword($_POST['administrator_password']))). "'),
('3','app_app_name','" . trim(addslashes($_POST['app_name'])). "'),
('4','app_app_short_name','" . trim(addslashes($_POST['app_short_name'])). "'),
('5','app_email_label','" . trim(addslashes($_POST['email_label'])). "'),
('6','app_default_skin','qdPM'),
('7','sf_default_timezone','America/New_York'),
('8','sf_default_culture','en'),
('9','app_rows_per_page','15'),
('10','app_custom_short_date_format','M d, Y'),
('11','app_custom_logn_date_format','M d, Y H:i'),
('12','app_allow_adit_tasks_comments_date','off'),
('13','app_show_menu_icons','off'),
('14','app_show_footer_links','off'),
('15','app_tasks_fields_tasks_version','off'),
('16','app_tasks_fields_tasks_phase','on'),
('17','app_tasks_fields_tasks_group','off'),
('18','app_tasks_fields_priority','on'),
('19','app_tasks_fields_label','on'),
('20','app_tasks_fields_id','off'),
('21','app_tasks_fields_name','on'),
('22','app_tasks_fields_status','on'),
('23','app_tasks_fields_assigned_to','on'),
('24','app_tasks_fields_created_by','off'),
('25','app_tasks_fields_estimated_time','on'),
('26','app_tasks_fields_start_date','off'),
('27','app_tasks_fields_due_date','on'),
('28','app_tasks_fields_progress','off'),
('29','app_tasks_fields_created_at','off'),
('30','app_use_skins','on'),
('31','app_use_related_tasks','on'),
('32','app_use_public_tickets','on'),
('33','app_public_tickets_show_login_link','off'),
('34','app_public_tickets_allow_attachments','on'),
('35','app_use_project_phases','on'),
('36','app_use_project_versions','on'),
('37','app_use_project_discussions','on'),
('38','app_use_tasks_groups','on'),
('39','app_use_tasks_timetracker','on'),
('40','app_use_fck_editor','on'),
('41','app_notify_all_project_team','off'),
('42','app_notify_all_customers','off'),
('43','app_use_single_email','off'),
('44','app_single_email_addres_from',''),
('45','app_single_name_from',''),
('46','app_use_smtp','off'),
('47','app_smtp_server',''),
('48','app_smtp_port','25'),
('49','app_smtp_encryption',NULL),
('50','app_smtp_login',''),
('51','app_smtp_pass',''),
('52','app_use_ldap_login','off'),
('53','app_ldap_host',''),
('54','app_ldap_port',''),
('55','app_ldap_base_dn',''),
('56','app_ldap_version','3'),
('57','app_use_email_notification','on'),
('58','app_show_user_email','off'),
('59','app_show_user_photo','on'),
('60','app_tasks_fields_type','off'),
('61','app_login_page_heading','Welcome to qdPM'),
('62','app_login_page_content',''),
('63','app_new_user_email_subject',NULL),
('64','app_new_user_email_body',''),
('65','app_amount_previous_comments','2'),
('66','app_rows_limit','150'),
('67','app_tasks_columns_list','TasksGroups,Versions,ProjectsPhases,TasksPriority,Name,TasksStatus,TasksTypes,AssignedTo,EstimatedTime,WorkHours,DueDate'),
('68','app_send_email_to_owner','off'),
('69','app_public_tickets_use_antispam','on'),
('70','app_app_logo',''),
('71','app_use_javascript_dropdown','on');
";  
  
  $restore_query .= $configuration_query;
  
  $restore_query = explode(';',$restore_query);
  
  //echo '<pre>';
  //print_r($restore_query);
  
  foreach($restore_query as $query)
  {
    if(strlen(trim($query))>0)tep_db_query(trim($query));
  }
  

  $db_config = '  
all:
  doctrine:
    class: sfDoctrineDatabase
    param:
      dsn: \'mysql:dbname=' . $database . ';host=' . $server  . (strlen($port)>0 ? ';port=' . $port:'') . '\'
      profiler: false
      username: ' . $username . '
      password: "<?php echo urlencode(\'' . $password . '\') ; ?>"
      attributes:
        quote_identifier: true  
  ';
    
  if(is_file('../core/config/databases.yml'))
  {
    @unlink('../core/config/databases.yml');
  }
  
  file_put_contents('../core/config/databases.yml',$db_config,FILE_TEXT|FILE_APPEND|LOCK_EX);
      
  header('Location: index.php?step=success');
  
  exit();
