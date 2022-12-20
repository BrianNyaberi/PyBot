<?php
/**
* WORK SMART
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



