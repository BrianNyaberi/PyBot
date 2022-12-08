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

<h3 class="page-title"><?php echo __('General Configuration') ?></h1>

<h3 class="form-section"><?php echo __('Root Administrator'); ?></h3>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_administrator_email"><?php echo __('Administrator Email'); ?></label>
  <div class="col-md-9">	
	 <?php echo input_tag('cfg[app_administrator_email]', sfConfig::get('app_administrator_email'),array('class'=>'form-control input-xlarge')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_administrator_password" ><?php echo __('Administrator Password'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_administrator_password]', '',array('class'=>'form-control input-xlarge','type'=>'password')); ?>
    <span class="help-block">
			<?php echo __('Root administrator is internal user who can just manage users and configuration and can’t create tasks or projects') ?>
		</span>
  </div>			
</div>


<h3 class="form-section"><?php echo __('Application'); ?></h3>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_app_name"><?php echo __('Name of application'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_app_name]', sfConfig::get('app_app_name'),array('class'=>'form-control input-xlarge')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_app_short_name"><?php echo __('Short name of application'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_app_short_name]', sfConfig::get('app_app_short_name'),array('class'=>'form-control input-xlarge')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_app_logo_file"><?php echo __('Logo'); ?> (215x45)</label>
  <div class="col-md-9">	
<?php
    echo  input_file_tag('cfg_app_app_logo_file')  . input_hidden_tag('cfg[app_app_logo]', sfConfig::get('app_app_logo'),array('size'=>'40')); 
      if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_app_logo')))
      {
        echo '<div>' . sfConfig::get('app_app_logo') . '</div>' . input_checkbox_tag('delete_logo') . ' <label for="delete_logo">' . __('Delete') . '</label>';
      }  
?>      

    <span class="help-block">
       (*.jpg, *.png, *.gif)
    </span>
  </div>			
</div>




<?php

  $timezone_list = array();
  $timezone_identifiers = DateTimeZone::listIdentifiers();
  for ($i=0; $i < sizeof($timezone_identifiers); $i++) 
  {    
      $timezone_list[$timezone_identifiers[$i]] = $timezone_identifiers[$i];
  }

  
?>

<h3 class="form-section"><?php echo __('Defaults'); ?></h3>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_sf_default_timezone"><?php echo __('Default Timezone'); ?></label>
  <div class="col-md-9">	
	   <?php echo select_tag('cfg[sf_default_timezone]',sfConfig::get('sf_default_timezone'), array('choices'=>$timezone_list),array('class'=>'form-control input-large')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_sf_default_culture"><?php echo __('Default Culture'); ?></label>
  <div class="col-md-9">	
	   <?php echo languages_select_tag('cfg[sf_default_culture]', sfConfig::get('sf_default_culture'),array('class'=>'form-control  input-small')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_rows_per_page"><?php echo __('Default Rows Per Page'); ?></label>
  <div class="col-md-9">	
	   <?php echo input_tag('cfg[app_rows_per_page]', sfConfig::get('app_rows_per_page'),array('class'=>'form-control input-small')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_rows_limit"><?php echo __('Rows Limit'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_rows_limit]', sfConfig::get('app_rows_limit',1000),array('class'=>'form-control input-small')); ?>
    <span class="help-block">
      <?php echo __('This option allows you to limit the number of rows retrieved in a single database query.<br>You can use this option if your server has problems displaying a lot of data.<br>Once you set this option, the query results will be displayed in paged format and you will see the extra PHP pager at the bottom of the page that will allow you to fetch more.') ?>
    </span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_custom_short_date_format"><?php echo __('Default Date Format'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_custom_short_date_format]', sfConfig::get('app_custom_short_date_format'),array('class'=>'form-control input-small')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_custom_logn_date_format"><?php echo __('Default Comments Date Format'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_custom_logn_date_format]', sfConfig::get('app_custom_logn_date_format'),array('class'=>'form-control input-small')); ?>
  </div>			
</div>
  
