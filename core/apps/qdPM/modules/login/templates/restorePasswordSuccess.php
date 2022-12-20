<?php
/**
* WORK SMART
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
