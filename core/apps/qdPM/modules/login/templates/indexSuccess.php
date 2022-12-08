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

<?php $form->setDefault('email',base64_decode($sf_request->getCookie('remember_user'))) ?>

<form class="login-form" name="loginForm" id="loginForm" action="<?php echo url_for('login/index',true) ?>" method="POST">

<?php echo $form->renderHiddenFields(false) ?>
<?php echo $form->renderGlobalErrors() ?>

<h3 class="form-title"><?php echo (strlen($h = sfConfig::get('app_login_page_heading'))>0 ? $h :__('Login')) ?></h3>

<div><?php if(strlen($c = strip_tags(sfConfig::get('app_login_page_content')))>0) echo '<p>' . nl2br($c) . '</p>' ?></div>

<?php if($sf_user->hasFlash('userNotices')) include_partial('global/userNotices', array('userNotices' => $sf_user->getFlash('userNotices'))); ?>

<div class="form-group">
  <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
  <label class="control-label visible-ie8 visible-ie9"><?php echo $form['email']->renderLabelName() ?></label>
  <div class="input-icon">
  	<i class="fa fa-envelope"></i>
  	<input class="form-control placeholder-no-fix required email" type="text" autocomplete="off" placeholder="<?php echo $form['email']->renderLabelName() ?>" name="login[email]"/>
  </div>
</div>

<div class="form-group">
	<label class="control-label visible-ie8 visible-ie9"><?php echo $form['password']->renderLabelName() ?></label>
	<div class="input-icon">
		<i class="fa fa-lock"></i>
		<input class="form-control placeholder-no-fix required" type="password" autocomplete="off" placeholder="<?php echo $form['password']->renderLabelName() ?>" name="login[password]"/>
	</div>
</div>

<div class="form-actions">			
      
     <label class="checkbox"> <?php echo input_checkbox_tag('remember_me',1,array('checked'=>$sf_request->getCookie('remember_me')))  . __('Remember Me') ?></label>
      
			<button type="submit" class="btn btn-info pull-right"><?php echo __('Login') ?> </button>
</div>

<div class="forget-password">	
	<p><?php echo link_to(__('Password forgotten?'),'login/restorePassword'); ?></p>
</div>

<?php
if(sfConfig::get('app_use_ldap_login')=='on')
{
  echo '
  <div class="create-account">	
	 <p>' . link_to('LDAP Login','login/ldap') . '</p>
  </div>';
}
?>

  <?php
  $http_referer = '';
      
  if(isset($_SERVER['REQUEST_URI']))
  {
    if(!stristr($_SERVER['REQUEST_URI'],'/login') and !stristr($_SERVER['REQUEST_URI'],'/create') and !stristr($_SERVER['REQUEST_URI'],'/edit') and !stristr($_SERVER['REQUEST_URI'],'/update') and !stristr($_SERVER['REQUEST_URI'],'/new'))
    {
      if(isset($_SERVER['HTTPS']))
      {
        $http_referer = ($_SERVER['HTTPS']=='on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      }
      else
      {
        $http_referer = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      }
    }
  }
  
  echo input_hidden_tag('http_referer', $http_referer); ?>
  
</form>

<?php include_partial('global/formValidator',array('form_id'=>'loginForm')); ?>
