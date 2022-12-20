<?php
/**
* WORK SMART
*/
?>
<?php 
  if($sf_request->hasParameter('projects_id'))
  {
     echo ajax_modal_template(__('New Task'),get_partial('form', array('form' => $form)));
  }
  elseif(isset($choices['']) and count($choices)==1)
  {
?>

  <div class="modal-header">
  	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  	<h4 class="modal-title"><?php echo __('Projects Not Found') ?></h4>
  </div>
  <div class="modal-body">  
      <?php echo __('Projects Not Found'); ?>
  </div>  
    
<?php    
  }  
  else
  {     
?>

  <div class="modal-header">
  	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  	<h4 class="modal-title"><?php echo __('New Task') ?></h4>
  </div>  

<form class="form-horizontal" role="form">
<div class="modal-body" style="padding-bottom: 0px;">
  <div class="form-body" >
  
  <div class="form-group" style="margin-bottom: 0px;">
  	<label class="col-md-3 control-label"> <?php echo __('Project')?></label>
  	<div class="col-md-9">
  		<?php echo select_tag('form_projects_id','',array('choices'  => $choices),array('onChange'=>'load_form_by_projects_id(\'form_container\',\'' . url_for('tasks/newTask') . '\',this.value)','class'=>'form-control')) ?>
  	</div>
  </div>

  </div>
</div>
</form>

<div id="form_container"></div>

<script type="text/javascript">
  $(document).ready(function(){ 
      load_form_by_projects_id('form_container','<?php echo url_for("tasks/newTask") ?>',$('#form_projects_id').val());            
  });     
</script>

<?php } ?>
