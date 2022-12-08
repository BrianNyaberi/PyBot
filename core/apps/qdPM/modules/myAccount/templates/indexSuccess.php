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
