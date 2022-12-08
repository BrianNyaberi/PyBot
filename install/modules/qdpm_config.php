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
<script language="javascript" type="text/javascript">
  function from_check(form)  
  {        
    error_msg = ''; 
        
    if(form.administrator_email.value.length == 0)
    { 
      error_msg += '* Administrator Email Address required \n';
    }
    else
    {      
      if(!email_validation(form.administrator_email.value))
      {
        error_msg += '* Invalid Administrator Email Address\n'; 
      }
    }   
      
    if(form.administrator_password.value.length == 0) error_msg += '* Administrator Password required \n';
    if(form.app_name.value.length == 0) error_msg += '* Application name required \n';
    if(form.app_short_name.value.length == 0) error_msg += '* Application short name required \n';
    
    if(error_msg!='')
    {
      alert(error_msg);
      return false;
    }        
    
    return true;
  }
  
  function email_validation(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;   
   if(reg.test(email) == false) {      
      return false;
   }
   
   return true;
  } 
  
</script>

<div class="infoBlock"><h1>qdPM config</h1></div>

<br><font color="red">* Required information</font>


<form name="db_config" action="index.php?step=qdpm_config&action=install_qdpm" method="post" onsubmit="return from_check(this);">
<table class="formTable">
  <tr>
    <td colspan="2"><b>Administrator access</b></td>
   </tr> 
  <tr>
    <td>Email:<font color="red">*</font></td>
    <td><input type="text" name="administrator_email"  value="admin@localhost.com"></td>
  </tr> 
  <tr>
    <td>Password:<font color="red">*</font></td>
    <td><input type="password" name="administrator_password" ></td>
  </tr>  
</table>

Administrator is internal user who can just manage users and configuration and can’t create tasks or projects.<br>
So after installation login as administrator and create users with user rights.<br>

<table class="formTable">
  <tr>
    <td><b>Basic Configuration</b></td>
  </tr> 
  <tr>
    <td>Application name:<font color="red">*</font></td>
    <td><input type="text" name="app_name"  value="Workspace" size="40"></td>
    <td>use in page heading</td>
  </tr> 
  <tr>
    <td>Short name:<font color="red">*</font></td>
    <td><input type="text" name="app_short_name"  value="qdPM" size="5"> use in page title</td>
    
  </tr>
  <tr>
    <td>Email label:</td>
    <td><input type="text" name="email_label"  value="qdPM - "  size="5"> use in email subject and can be blank</td>
    
  </tr>

</table>

<div><input type="submit" value="Save" class="btn"></div>

<input type="hidden" name="db_host"  value="<?php echo trim(addslashes($_POST['db_host']))?>">
<input type="hidden" name="db_port" value="<?php echo trim(addslashes($_POST['db_port']))?>">
<input type="hidden" name="db_name" value="<?php echo trim(addslashes($_POST['db_name']))?>">
<input type="hidden" name="db_username" value="<?php echo trim(addslashes($_POST['db_username']))?>">
<input type="hidden" name="db_password" value="<?php echo trim(addslashes($_POST['db_password']))?>">

</form>
 
