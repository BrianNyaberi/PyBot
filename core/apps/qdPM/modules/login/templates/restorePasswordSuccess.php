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

<form class="lforget-form"  id="restorePassword" action="<?php echo url_for('login/restorePassword') ?>" method="POST">

<?php echo $form->renderHiddenFields(false) ?>
<?php echo $form->renderGlobalErrors() ?>

<h3><?php echo __('Password forgotten?') ?></h3>
<p><?php echo __('Enter your e-mail address below') ?></p>

<?php if($sf_user->hasFlash('userNotices')) include_partial('global/userNotices', array('userNotices' => $sf_user->getFlash('userNotices'))); ?>

<div class="form-group">
	<div class="input-icon">
		<i class="fa fa-envelope"></i>
		<input class="form-control placeholder-no-fix required email" type="text" autocomplete="off" placeholder="Email" name="restorePassword[email]"/>
	</div>
</div>
<div class="form-actions">
	<button type="button" id="back-btn" class="btn btn-default" onClick="location.href='<?php echo url_for('login/index')?>'"><i class="fa fa-arrow-circle-left"></i> </button>
	<button type="submit" class="btn btn-info pull-right"><?php echo __('Send New Password') ?> </button>
</div>
    
  
</form>

<?php include_partial('global/formValidator',array('form_id'=>'restorePassword')); ?>
