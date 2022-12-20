<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('My Account') ?></h3>


<div class="portlet">
  <div class="portlet-title">
  	<div class="caption">
  		<i class="fa fa-reorder"></i><?php echo __('Details') ?>
  	</div>
  </div>
  <div class="portlet-body form">
            
<form class="form-horizontal" role="form"  id="users" action="<?php echo url_for('myAccount/update') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
    	<label class="col-md-3 control-label"><?php echo $form['new_password']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['new_password'] ?>
    	</div>
    </div>  
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['email']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['email'] ?>
        <div id="email_error" class="error"></div>
    	</div>
    </div>
    
    <?php echo ExtraFieldsList::renderFormFileds('users',$form->getObject(),$sf_user)?>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['photo']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['photo'] ?>
        <div><?php if(strlen($form['photo']->getValue())>0) echo renderUserPhoto($form['photo']->getValue())  . '<br>'. $form['remove_photo'] . ' ' . $form['remove_photo']->renderLabel() ?></div>
    	</div>
    </div> 
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['culture']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['culture'] ?>
    	</div>
    </div>
    
</div>     
  
  
  
<div class="form-actions fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-offset-3 col-md-9">
				
        <input type="button" class="btn btn-primary" value="<?php echo __('Save')?>" id="submit_button" onClick="check_user_form('users','<?php echo url_for('myAccount/checkUser') ?>')"/>                   
        <div id="loading" ></div>

			</div>
		</div>
	</div>
</div>  
  

</form>

  </div>
</div>

<?php include_partial('global/formValidator',array('form_id'=>'users')); ?>

<script type="text/javascript">    
  $(function() {                             
    $("#submit_button").click(function() {
        $("#users").valid()                              
    });              
  });
</script> 
