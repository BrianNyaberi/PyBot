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
<?php if($form->getObject()->isNew()) $form->setDefault('projects_id',$sf_request->getParameter('projects_id')) ?>
<?php if($form->getObject()->isNew()) $form->setDefault('users_id',$sf_user->getAttribute('id')) ?>
<?php if($sf_request->getParameter('projects_id')>0){ $projects = Doctrine_Core::getTable('Projects')->find($sf_request->getParameter('projects_id')); $edit_projects = Users::hasAccess('edit','projects',$sf_user,$sf_request->getParameter('projects_id')); }else{ $edit_projects = false; }?>

<form class="form-horizontal" role="form" id="tickets" action="<?php echo url_for('tickets/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php print 'enctype="multipart/form-data" ' ?>>
<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

<?php echo $form->renderHiddenFields(false) ?>
<?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) ?>
<?php echo input_hidden_tag('redirect_to',$sf_request->getParameter('redirect_to')) ?>

<?php echo $form->renderGlobalErrors() ?>

<?php include_component('app','copyToRelated',array('form_name'=>'tickets')) ?>

<ul class="nav nav-tabs">
	<li class="active">
    <a href="#tab_general" data-toggle="tab"><?php echo __('General') ?></a>
  </li>	        	
  <li>
    <a href="#tab_attachments" data-toggle="tab"><?php echo __('Attachments') ?></a>
  </li>
</ul>
        
<div class="tab-content" >
  <div class="tab-pane fade active in" id="tab_general">         
        
      <?php if($sf_request->getParameter('projects_id')>0): ?>
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['departments_id']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['departments_id'] ?>
      	</div>
      </div> 
      <?php endif ?>
      
      <?php if(app::countItemsByTable('TicketsTypes')>0): ?>
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['tickets_types_id']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['tickets_types_id'] ?>
      	</div>
      </div> 
      <?php endif ?>
                  
      <?php if(app::countItemsByTable('TicketsStatus')>0): ?>
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['tickets_status_id']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['tickets_status_id'] ?>
      	</div>
      </div>
      <?php endif ?>             
      
      
      <div class="form-group">
      	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['name']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['name'] ?>
      	</div>
      </div> 
      
      
      <?php echo ExtraFieldsList::renderFormFiledsByType('tickets',$form->getObject(),$sf_user,'input')?>
      
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['description']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['description'] ?>
      	</div>
      </div>
      
      <?php echo ExtraFieldsList::renderFormFiledsByType('tickets',$form->getObject(),$sf_user,'text')?>
      <?php echo ExtraFieldsList::renderFormFiledsByType('tickets',$form->getObject(),$sf_user,'file')?>
      
      <?php if($sf_request->getParameter('projects_id')>0 and $edit_projects  and $form->getObject()->isNew()): ?>
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['users_id']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['users_id'] ?>
      	</div>
      </div>
      <?php endif ?>
      
      <?php if($form->getObject()->isNew()):?>      
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo __('Notify') ?></label>
      	<div class="col-md-9">
      		<?php echo select_tag('extra_notification','',array('choices'=>Users::getChoices(array_filter(explode(',',$projects->getTeam())),'tickets'),'multiple'=>true,'expanded'=>true),array('class'=>'form-control')) ?>
      	</div>
      </div>
      <?php endif ?>
      
  </div>
                      
  <div class="tab-pane fade" id="tab_attachments">
    <?php include_component('attachments','attachments',array('bind_type'=>'tickets','bind_id'=>($form->getObject()->isNew()?0:$form->getObject()->getId()))) ?>
  </div>
  
</div>


<?php include_partial('global/formValidatorExt',array('form_id'=>'tickets')); ?>
  
  </div>
</div>
        
<?php echo ajax_modal_template_footer() ?>    
  
</form>

