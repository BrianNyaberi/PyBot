<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo __('Move To') ?></h4>
</div>


<form class="form-horizontal" role="form" id="moveToForm" action="<?php echo url_for('discussions/moveTo') ?>" method="post">
<div class="modal-body">
  <div class="form-body">
        
<?php echo input_hidden_tag('redirect_to',$sf_request->getParameter('redirect_to')) ?>
<?php if($sf_request->getParameter('projects_id')>0) echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) ?>
<?php echo input_hidden_tag('discussions_id',$sf_request->getParameter('discussions_id')) ?>

<div class="form-group">
	<label class="col-md-3 control-label"><?php echo __('Move To Project') ?></label>
	<div class="col-md-9">
		<?php echo select_tag('move_to','',array('choices'=>Projects::getChoices('tasks',$sf_user)),array('class'=>'form-control required')) ?>
    <span class="help-block">
      <?php echo __("Note: Destination Project Team should include all Users assigned to selected items.") .'<br>' . __("Users won't have access to Items if they are assigned to these Items but are not in the Project Team")?>
    </span>
	</div>
</div>

<?php echo input_hidden_tag('selected_items',$sf_request->getParameter('tasks_id')) ?>

  </div>
</div>

<?php echo ajax_modal_template_footer(__('Move')) ?>

</form>

<script>
  set_selected_items();
</script>

<?php include_partial('global/formValidator',array('form_id'=>'moveToForm')); ?>

