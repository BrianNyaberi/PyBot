<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Import Spreadsheet (Step 1)')?></h3>

<p><?php echo __('You can import tasks from spreadsheet in xls format.')?></p>

<form class="form-horizontal" role="form"  id="import" action="<?php echo url_for('tools/xlsTasksImport')?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="sf_method" value="put" />

  <div class="form-group">
  	<label class="col-md-3 control-label"><?php echo __('Project') ?></label>
  	<div class="col-md-9">
  		<?php echo select_tag('projects_id',$sf_request->getParameter('project'),array('choices'=>Projects::getChoices('',$sf_user)),array('class'=>'form-control input-large required'))?>
  	</div>
  </div> 
  
  <div class="form-group">
  	<label class="col-md-3 control-label"><?php echo __('File') ?></label>
  	<div class="col-md-9">
  		<input type="file" name="import_file" class="required">
  	</div>
  </div>   
    
  <?php echo submit_tag(__('Load file and bind (map) columns to fields')) ?>
</form>

<?php include_partial('global/formValidator',array('form_id'=>'import')); ?>
