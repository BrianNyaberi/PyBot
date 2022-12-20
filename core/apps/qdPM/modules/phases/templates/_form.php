<?php
/**
* WORK SMART
*/
?>

<form class="form-horizontal" role="form"  id="phases" action="<?php echo url_for('phases/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

    <?php echo $form->renderHiddenFields(false) ?>
    <?php echo $form->renderGlobalErrors() ?>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['name']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['name'] ?>
    	</div>
    </div>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['default_values']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['default_values'] ?>
        <span class="help-block">
          <?php echo __('Put phases in new line, for example') . ':<br>Design<br>Development<br>Site Test'; ?>
        </span>
    	</div>
    </div>
      
  </div>  
</div>  

<?php echo ajax_modal_template_footer() ?>  
</form>

<?php include_partial('global/formValidator',array('form_id'=>'phases')); ?>
