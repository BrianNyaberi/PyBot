<?php
/**
* WORK SMART
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
  

