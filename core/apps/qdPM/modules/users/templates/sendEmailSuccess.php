<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Send email to active users')?></h3>


<div class="portlet">
  <div class="portlet-title">
  	<div class="caption">
  		<i class="fa fa-reorder"></i><?php echo __('Details') ?>
  	</div>
  </div>
  <div class="portlet-body form">
  
<form class="form-horizontal " role="form" id="send" action="<?php echo url_for('users/sendEmail') ?>" method="post">
<div class="form-body">
<input type="hidden" name="sf_method" value="put" />

  <div class="form-group">
  	<label class="col-md-3 control-label"> <?php echo __('From') ?></label>
  	<div class="col-md-9">
  		<p class="form-control-static"><?php echo $user->getName() . ' &lt;' . $user->getEmail(). '&gt;' ?></p>
  	</div>
  </div>
  
  <div class="form-group">
  	<label class="col-md-3 control-label"> <?php echo __('To') ?><br><a href="#" onClick="return checkAllInContainer('users_groups_container')"><small><?php echo __('Select All')?></small></a></label>
  	<div class="col-md-9">
  		<div id="users_groups_container"><?php echo select_tag('users_groups','',array('choices'=>UsersGroups::getChoicesByType(),'multiple'=>true,'expanded'=>true)) ?></div>
  	</div>
  </div>
  
  <div class="form-group">
  	<label class="col-md-3 control-label"> <?php echo __('Subject') ?></label>
  	<div class="col-md-9">
  		<?php echo input_tag('subject',$sf_request->getParameter('subject'),array('class'=>'form-control input-xlarge required')) ?>
  	</div>
  </div>
  
  <div class="form-group">
  	<label class="col-md-3 control-label"> <?php echo __('Message') ?></label>
  	<div class="col-md-9">
  		<?php echo textarea_tag('message',$sf_request->getParameter('message'),array('class'=>'form-control editor')) ?>
  	</div>
  </div>


</div>



<div class="form-actions fluid">
	<div class="col-md-offset-3 col-md-9">
		<?php echo submit_tag(__('Send Message')) ?>
	</div>
</div>  


</form>


  </div>
</div>

<?php include_partial('global/formValidator',array('form_id'=>'send')); ?>

