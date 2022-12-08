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
<h3 class="page-title"><?php echo __('Configure Email notification for new user') ?></h1>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_new_user_email_subject"><?php echo __('Subject'); ?></label>
  <div class="col-md-9">	
	 <?php echo input_tag('cfg[app_new_user_email_subject]', sfConfig::get('app_new_user_email_subject'),array('class'=>'form-control')); ?>
   <span class="help-block">
			<?php echo  '<b>' . __('Default') . ':</b> ' . __('Your account has been created in') . ' ' . sfConfig::get('app_app_name')?>
		</span>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_new_user_email_body"><?php echo __('Body'); ?></label>
  <div class="col-md-9">	
	 <?php echo textarea_tag('cfg[app_new_user_email_body]', sfConfig::get('app_new_user_email_body'),array('class'=>'form-control')); ?>
   <span class="help-block">
			<?php echo __('Use Keys') ?>:<br>        
      [user_name] - <?php echo __('to include User Name') ?><br> 
      [login_details] - <?php echo __('to include Login Details') ?><br>
		</span>   
  </div>			
</div>



