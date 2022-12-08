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
<h3 class="page-title"><?php echo __('Email Options') ?></h1>

<h3 class="form-section"><?php echo __('Notifications'); ?></h3>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_email_notification"><?php echo __('Use email notification'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_use_email_notification]',sfConfig::get('app_use_email_notification'), array('choices'=>$default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_email_label"><?php echo __('Email Subject Label'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_email_label]', sfConfig::get('app_email_label'),array('class'=>'form-control input-large')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_notify_all_customers"><?php echo __('Amount previous comments in email'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_amount_previous_comments]',sfConfig::get('app_amount_previous_comments',2), array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_email_notification"><?php echo __('Copy Sender?'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_send_email_to_owner]',sfConfig::get('app_send_email_to_owner'), array('choices'=> $default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_notify_all_tasks"><?php echo __('Notify all project team when task created or task comment added'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_notify_all_tasks]',sfConfig::get('app_notify_all_tasks'), array('choices'=> $default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_notify_all_tickets"><?php echo __('Notify all project team when ticket created or ticket comment added'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_notify_all_tickets]',sfConfig::get('app_notify_all_tickets'), array('choices'=> $default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_notify_all_discussions"><?php echo __('Notify all project team when discussion created or discussion comment added'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_notify_all_discussions]',sfConfig::get('app_notify_all_discussions'), array('choices'=> $default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>


<h3 class="form-section"><?php echo __('Email address from'); ?></h3>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_single_email"><?php echo __('Send all emails from single email address'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_use_single_email]',sfConfig::get('app_use_single_email'), array('choices'=>$default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_single_email_addres_from"><?php echo __('Email address from'); ?></label>
  <div class="col-md-9">	
    <?php echo input_tag('cfg[app_single_email_addres_from]', sfConfig::get('app_single_email_addres_from'),array('class'=>'form-control input-large')); ?>	   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_single_name_from"><?php echo __('Name from'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_single_name_from]', sfConfig::get('app_single_name_from'),array('class'=>'form-control input-large')); ?>   
  </div>			
</div>

<h3 class="form-section"><?php echo __('SMTP Configuration'); ?></h3>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_smtp"><?php echo __('Use SMTP'); ?></label>
  <div class="col-md-9">	
  	<?php echo select_tag('cfg[app_use_smtp]',sfConfig::get('app_use_smtp'),array('choices'=>$default_selector),array('class'=>'form-control input-small')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_smtp_server"><?php echo __('SMTP Server'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_smtp_server]', sfConfig::get('app_smtp_server'),array('class'=>'form-control input-large')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_smtp_port"><?php echo __('SMTP Port'); ?></label>
  <div class="col-md-9">	
    <?php echo input_tag('cfg[app_smtp_port]', sfConfig::get('app_smtp_port'),array('class'=>'form-control input-small')); ?>	   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_smtp_encryption"><?php echo __('SMTP Encryption'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_smtp_encryption]', sfConfig::get('app_smtp_encryption'),array('class'=>'form-control input-small')); ?>
    <span class="help-block">
      <?php echo implode('/',stream_get_transports()) ?>
    </span>     
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_smtp_login"><?php echo __('SMTP Login'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_smtp_login]', sfConfig::get('app_smtp_login'),array('class'=>'form-control input-large')); ?>   
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_smtp_pass"><?php echo __('SMTP Password'); ?></label>
  <div class="col-md-9">	
  	<?php echo input_tag('cfg[app_smtp_pass]', sfConfig::get('app_smtp_pass'),array('class'=>'form-control input-large')); ?>   
  </div>			
</div>
