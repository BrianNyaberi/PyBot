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
   
