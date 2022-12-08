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

<?php if($form->getObject()->isNew()) $form->setDefault('projects_id',$sf_request->getParameter('projects_id'))?>

<form class="form-horizontal" role="form" id="projectsPhases" action="<?php echo url_for('projectsPhases/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

<?php echo $form->renderHiddenFields(false) ?>
<?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id') )?>
<?php echo $form->renderGlobalErrors() ?>



    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['phases_status_id']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['phases_status_id'] ?>
    	</div>
    </div>

    <div class="form-group">
    	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['name']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<?php echo $form['name'] ?>
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

<?php include_partial('global/formValidator',array('form_id'=>'projectsPhases')); ?>

