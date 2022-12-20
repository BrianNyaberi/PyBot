<?php
/**

*/
?>
<?php

if(!is_file('core/config/databases.yml'))
{
  header('location: install/index.php');
  exit();  
}

/**
 *check server configureatoin
 */ 
if(!version_compare(phpversion(), '5.2.4', '>=')) die("ERROR: requires PHP >= 5.2.4', 'Current version is " .phpversion());
if(ini_get('zend.ze1_compatibility_mode')) die("ERROR: php.ini - requires zend.ze1_compatibility_mode set to off");
if(ini_get('arg_separator.output')!='&') die("ERROR: php.ini - requires arg_separator.output set to &");

if((int)ini_get('memory_limit')<32)
{
  echo die("ERROR  php.ini - requires memory_limit 32M or more");
}
if(!class_exists('PDO'))
{ 
  die("ERROR: Install PDO and PDO driver: mysql");
}
else
{
  $drivers = PDO::getAvailableDrivers();
  if(!in_array('mysql',$drivers))
  {
    die("ERROR: Install PDO driver: mysql");
  }
}  

if(!class_exists('DomDocument')) die("ERROR: Install the php-xml module");
