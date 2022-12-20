<?php
/**
* WORK SMART
*/
?>

<?php if($form->getObject()->isNew()) $form->setDefault('projects_id',$sf_request->getParameter('projects_id'))?>

<form class="form-horizontal" role="form" id="versions" action="<?php echo url_for('versions/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

<?php echo $form->renderHiddenFields(false) ?>
<?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id') )?>
<?php echo $form->renderGlobalErrors() ?>
  
  
    <?php if(app::countItemsByTable('VersionsStatus')>0): ?>
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['versions_status_id']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['versions_status_id'] ?>
    	</div>
    </div>
    <?php endif ?> 
  
  
    <div class="form-group">
    	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['name']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['name'] ?>
    	</div>
    </div> 
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['description']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['description'] ?>
    	</div>
    </div>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['due_date']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['due_date'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div> 

  </div>
</div>

<?php echo ajax_modal_template_footer() ?>  

</form>

<?php include_partial('global/formValidator',array('form_id'=>'versions')); ?>


