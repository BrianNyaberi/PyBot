<?php
/**
*qdPM
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@qdPM.net so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade qdPM to newer
* versions in the future. If you wish to customize qdPM for your
* needs please refer to http://www.qdPM.net for more information.
*
* @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
