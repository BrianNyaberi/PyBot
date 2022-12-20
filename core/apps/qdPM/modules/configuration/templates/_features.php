<?php
/**
* WORK SMART
*/
?>

<h3 class="page-title"><?php echo __('Features') ?></h1>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_skins"><?php echo __('Use Skins'); ?></label>
  <div class="col-md-9">	
	  <?php echo select_tag('cfg[app_use_skins]',sfConfig::get('app_use_skins'),array('choices'=>$default_selector),array('class'=>'form-control input-small')); ?> 
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_project_phases"><?php echo __('Use Project Phases'); ?></label>
  <div class="col-md-9">	
	  <?php echo select_tag('cfg[app_use_project_phases]',sfConfig::get('app_use_project_phases'),array('choices'=>$default_selector),array('class'=>'form-control input-small')) ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_project_versions"><?php echo __('Use Project Versions'); ?></label>
  <div class="col-md-9">	
	  <?php echo select_tag('cfg[app_use_project_versions]',sfConfig::get('app_use_project_versions'),array('choices'=>$default_selector),array('class'=>'form-control input-small')) ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_tasks_groups"><?php echo __('Use Tasks Groups'); ?></label>
  <div class="col-md-9">	
	 <?php echo select_tag('cfg[app_use_tasks_groups]',sfConfig::get('app_use_tasks_groups'),array('choices'=>$default_selector),array('class'=>'form-control input-small')) ?>  
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_allow_adit_tasks_comments_date"><?php echo __('Allow edit tasks comments date'); ?></label>
  <div class="col-md-9">	
	  <?php echo select_tag('cfg[app_allow_adit_tasks_comments_date]',sfConfig::get('app_allow_adit_tasks_comments_date'), array('choices'=>$default_selector),array('class'=>'form-control input-small')); ?> 
  </div>			
</div>
   
