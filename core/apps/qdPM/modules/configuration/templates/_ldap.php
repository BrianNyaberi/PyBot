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
<h3 class="page-title"><?php echo __('LDAP Configuration') ?></h1>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_use_ldap_login"><?php echo __('Use LDAP Login'); ?></label>
  <div class="col-md-9">	
	  <?php echo select_tag('cfg[app_use_ldap_login]',sfConfig::get('app_use_ldap_login'), array('choices'=>$default_selector),array('class'=>'form-control input-small')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_default_user_group"><?php echo __('Default Group'); ?></label>
  <div class="col-md-9">	
	  <?php echo select_tag('cfg[app_ldap_default_user_group]',sfConfig::get('app_ldap_default_user_group'), array('choices'=>UsersGroups::getChoicesByType(false,true)),array('class'=>'form-control input-small')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_host"><?php echo __('LDAP server name'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_server]', sfConfig::get('app_ldap_server'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo __('If using LDAP this is the hostname or IP address of the LDAP server. Alternatively you can specify a URL like ldap://hostname:port/') ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_port"><?php echo __('LDAP server port'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_port]', sfConfig::get('app_ldap_port'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo __('Optionally you can specify a port which should be used to connect to the LDAP server instead of the default port 389.') ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_base_dn"><?php echo __('LDAP base dn'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_base_dn]', sfConfig::get('app_ldap_base_dn'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo __('This is the Distinguished Name, locating the user information, e.g. o=My Company,c=US.') ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_uid"><?php echo __('LDAP uid'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_uid]', sfConfig::get('app_ldap_uid'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo __('This is the key under which to search for a given login identity, e.g. uid, sn, etc.') ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_version"><?php echo __('LDAP user filter'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_user_filter]', sfConfig::get('app_ldap_user_filter'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo __('Optionally you can further limit the searched objects with additional filters.<br>For example objectClass=posixGroup would result in the use of (&(uid=$username)(objectClass=posixGroup))')  ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_version"><?php echo __('LDAP e-mail attribute'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_email]', sfConfig::get('app_ldap_email'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo __('Set this to the name of your user entry e-mail attribute (if one exists) in order to automatically set the e-mail address for new users.<br>User email is required for qdPM and if it\'s not exist qdPM atumatically assign email like username@localhost.com') ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_user"><?php echo __('LDAP user dn'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_user]', sfConfig::get('app_ldap_user'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo  __('Leave blank to use anonymous binding. If filled in uses the specified distinguished name on login attempts to find the correct user, e.g. uid=Username,ou=MyUnit,o=MyCompany,c=US. ') ?>
		</span>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_ldap_password"><?php echo __('LDAP password'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_ldap_password]', sfConfig::get('app_ldap_password'),array('class'=>'form-control input-large')); ?>
    <span class="help-block">
			<?php echo  __('Leave blank to use anonymous binding. Otherwise fill in the password for the above user. <br><b>Warning:</b> This password will be stored as plain text in the database visible to everybody who can access your database or who can view this configuration page.') ?>
		</span>
  </div>			
</div> 

