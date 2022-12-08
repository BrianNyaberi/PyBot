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

<?php if($form->getObject()->isNew()) $form->setDefault('created_by',$sf_user->getAttribute('id')) ?>

<form class="form-horizontal" role="form"  action="<?php echo url_for('tasksComments/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php print 'enctype="multipart/form-data" ' ?>>
<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" id="sf_method" value="put" />
<?php endif; ?>

<?php echo $form->renderHiddenFields(false) ?>
<?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) ?>
<?php echo input_hidden_tag('tasks_id',$sf_request->getParameter('tasks_id')) ?>

<?php echo $form->renderGlobalErrors() ?>

<ul class="nav nav-tabs">
	<li class="active">
    <a href="#tab_general" data-toggle="tab"><?php echo __('General') ?></a>
  </li>      
  <li>
    <a href="#tab_attachments" data-toggle="tab"><?php echo __('Attachments') ?></a>
  </li>
</ul>



<div class="tab-content">
  <div class="tab-pane fade active in" id="tab_general">
                
        <?php if(app::countItemsByTable('TasksStatus')>0 and $form->getObject()->isNew()): ?>
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo $form['tasks_status_id']->renderLabel() ?></label>
        	<div class="col-md-9">
        		<?php echo $form['tasks_status_id'] ?>
        	</div>
        </div> 
        <?php endif ?>
        
        
  <?php if(Users::hasTasksAccess('edit',$sf_user,$tasks, $projects) and $form->getObject()->isNew()){ ?>
    
        <?php if(app::countItemsByTable('TasksPriority')>0): ?>              
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo $form['tasks_priority_id']->renderLabel() ?></label>
        	<div class="col-md-9">
        		<?php echo $form['tasks_priority_id'] ?>
        	</div>
        </div>
        <?php endif ?>
                                               
        <?php if(app::countItemsByTable('TasksLabels')>0): ?>
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo __('Label') ?></label>
        	<div class="col-md-9">
        		<?php echo select_tag('tasks_labels_id','',array('choices'=>app::getItemsChoicesByTable('TasksLabels',true)),array('class'=>'form-control input-large')) ?>
        	</div>
        </div>
        <?php endif ?>
        
        <?php if(app::countItemsByTable('TasksTypes')>0): ?>
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo __('Type') ?></label>
        	<div class="col-md-9">
        		<?php echo select_tag('tasks_types_id','',array('choices'=>app::getItemsChoicesByTable('TasksTypes',true)),array('class'=>'form-control input-large')) ?>
        	</div>
        </div>
        <?php endif ?>
        
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo $form['due_date']->renderLabel() ?></label>
        	<div class="col-md-9">
        		<div class="input-group input-medium date datepicker"><?php echo $form['due_date'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
        	</div>
        </div>

       
    <?php  } ?>
                     
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo $form['worked_hours']->renderLabel() ?></label>
        	<div class="col-md-9">
        		<?php echo $form['worked_hours'] ?>
        	</div>
        </div>
        
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo __('Progress') ?></label>
        	<div class="col-md-9">
        		<?php echo select_tag('progress',$tasks->getProgress(),array('choices'=>Tasks::getProgressChoices()),array('class'=>'form-control input-small')) ?>
        	</div>
        </div>

        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo $form['description']->renderLabel() ?></label>
        	<div class="col-md-9">
        		<?php echo $form['description'] ?>
        	</div>
        </div>

    <?php if(sfConfig::get('app_allow_adit_tasks_comments_date')=='on'):?>
        <div class="form-group">
        	<label class="col-md-3 control-label"> <?php echo $form['created_at']->renderLabel() ?></label>
        	<div class="col-md-9">
        		<div id="created-at-box"><?php echo $form['created_at'] ?></div>
        	</div>
        </div>
    <?php endif ?>

  </div>
  
  <div  class="tab-pane fade" id="tab_attachments">
    <?php include_component('attachments','attachments',array('bind_type'=>'comments','bind_id'=>($form->getObject()->isNew()?0:$form->getObject()->getId()))) ?>
  </div>
</div>


  </div>
</div>

<?php echo ajax_modal_template_footer() ?>

</form>

<script>
  $('#created-at-box select').addClass('form-control').css('display','inline').css('width','65px').css('margin','0 2px 0 2px')
  $('#tasks_comments_created_at_year').css('width','80px')
</script>


