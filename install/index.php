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
  error_reporting(E_ALL & ~E_NOTICE);
  
  include('lib/databaseHelper.php');
  
  $step = $_GET['step']??'environment';
       
  if($step=='qdpm_config') include('actions/check_db_settings.php');
  if(isset($_GET['action']) and $_GET['action']=='install_qdpm') include('actions/install_qdpm.php');
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />      
    <meta name="language" content="en" /> 
    <title>qdPM 9.1 Installation</title> 
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" /> 
    </head> 
  <body>      
  <div id="page"> 
  
    <div id="header-wrap"> 
      <div id="header"> 
        <div id="logo-section">qdPM 9.2 Installation</div>                  
      </div> 
    </div> 
 
    <div class="shadow-top"><div class="shadow-top-bar"></div></div>  
    <div id="center-wrap">  
      <div id="center"> 
        <div id="content"> 
                                                    
          <?php if($step=='environment') include('modules/checking_environment.php')?>
          <?php if($step=='database_config') include('modules/database_config.php')?>
          <?php if($step=='qdpm_config') include('modules/qdpm_config.php')?>
          <?php if($step=='success') include('modules/success.php')?>

 
        </div> 
                        
      </div> 
    </div> 
 
    <div class="patch_minheight"></div> 
    <div id="footer_guarantor"></div> 
  </div> 
  
    
 
  <div id="footer-wrap"> 
    <div class="shadow-bottom"></div> 
    <div id="footer"> 
      <div class="footer-text">       
        <br>
        qdPM 9.2 <br>      
       Copyright @ 2010 <a title="Project Management, Time Tracking, Support Tickets" class="footer-text" target="_blank" href="http://qdpm.net/">qdpm.net</a>
 
      </div> 
 
    </div> 
  </div> 
   
  </body> 
</html> 
