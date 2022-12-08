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


  function tep_db_connect($server, $username, $password,$database, $link = 'db_link',$params = array()) {    
    global $$link;
      
    $$link = mysqli_init();
    
    if (!$$link) {
        tep_db_error('mysqli_init failed',$params);
    }

    if (!mysqli_options($$link, MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
        tep_db_error('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed',$params);
    }
    
    if (!mysqli_options($$link, MYSQLI_INIT_COMMAND, 'SET NAMES utf8')) {
        tep_db_error('Setting MYSQLI_INIT_COMMAND failed',$params);
    }
    
    if (!@mysqli_real_connect($$link, $server, $username, $password, $database)) {
        tep_db_error('Error: (' . mysqli_connect_errno() . ') ' . mysqli_connect_error(),$params);
    }

    return $$link;    
  }

  function tep_db_query($query, $link = 'db_link') {
    global $$link;
    
    if(strlen(trim($query))>0)
    {
      $result = mysqli_query($$link, $query ) or die($query . '<br>' . mysqli_errno($$link) . '<br>' . mysqli_error($$link));
    }
            
    return $result;
  }
  
  function tep_db_error($error,$params = array())
  {
    header('Location: index.php?step=database_config&db_error=' . urlencode($error));
    exit();
  }
  

