<?php
/**
* WORK SMART
*/
?>
<?php
  $server = $_POST['db_host'];
  $port = $_POST['db_port'];
  $username = $_POST['db_username'];
  $password = $_POST['db_password'];
  $database = $_POST['db_name'];
  
  tep_db_connect($server . (strlen($port)>0 ? ':' . $port : ''),$username, $password, $database);
      
