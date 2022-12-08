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
<?php if($form->getObject()->isNew()) $form->setDefault('users_id',$sf_user->getAttribute('id')) ?>

<form class="form-horizontal" role="form"  id="tasksReports" action="<?php echo url_for('userReports/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

<?php echo $form->renderHiddenFields(false) ?>   
<?php echo input_hidden_tag('redirect_to',$sf_request->getParameter('redirect_to')) ?>

<?php echo $form->renderGlobalErrors() ?>


  <div class="form-group">
  	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['name']->renderLabel() ?></label>
  	<div class="col-md-9">
  		<?php echo $form['name'] ?>
  	</div>
  </div>

    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo $form['display_on_home']->renderLabel() ?></label>
    	<div class="col-md-9">
    		<div class="checkbox-list"><label class="checkbox-inline"><?php echo $form['display_on_home'] ?></label></div>
    	</div>
    </div>
        
    
    
<ul class="nav nav-tabs">
	<li class="active">
    <a href="#tab_tasks_filters" data-toggle="tab"><?php echo __('Tasks Filters') ?></a>
  </li>
	<li>
    <a href="#tab_extra" data-toggle="tab"><?php echo __('Extra') ?></a>
  </li>        	
  <li>
    <a href="#tab_projects_filters" data-toggle="tab"><?php echo __('Projects Filters') ?></a>
  </li>
</ul>    
   
   
<div class="tab-content" >
  <div class="tab-pane fade active in" id="tab_tasks_filters">
  
  <div class="row">
		<div class="col-md-6">
      <?php echo app::getReportFormFilterByTable('Status','user_reports[tasks_status_id]','TasksStatus',$form['tasks_status_id']->getValue()) ?>
      <?php      
        if(count($choices = Users::getChoices(array(),'tasks'))>0 and (Users::hasAccess('insert','tasks',$sf_user) or Users::hasAccess('edit','tasks',$sf_user)))
        { 
          if(!is_string($v = $form['assigned_to']->getValue())) $v = '';
          
          echo  '
            <div class="form-group">
            	<label class="col-md-3 control-label">' . __('Assigned To') . '</label>
            	<div class="col-md-9">          		
                ' . select_tag('user_reports[assigned_to]',explode(',',$v),array('choices'=>$choices,'multiple'=>true),array('class'=>'form-control multiple-select-tag','style'=>'')). '
            	</div>
            </div>
          ';         
        }                                      
      ?>
    </div>
    
    <div class="col-md-6">
      <?php echo app::getReportFormFilterByTable('Type','user_reports[tasks_type_id]','TasksTypes',$form['tasks_type_id']->getValue()) ?>
      <?php echo app::getReportFormFilterByTable('Label','user_reports[tasks_label_id]','TasksLabels',$form['tasks_label_id']->getValue()) ?>      
    </div>
  </div>  
  
  </div>
  
  <div class="tab-pane fade" id="tab_extra">
    
    <h3 class="form-section"><?php echo __('Tasks Due Date') ?></h3>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo __('From') ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['due_date_from'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo __('To') ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['due_date_to'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div>
    
    <h3 class="form-section"><?php echo __('Tasks Created') ?></h3>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo __('From') ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['created_from'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo __('To') ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['created_to'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div>
    
    <h3 class="form-section"><?php echo __('Closed Date') ?></h3>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo __('From') ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['closed_from'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div>
    
    <div class="form-group">
    	<label class="col-md-3 control-label"><?php echo __('To') ?></label>
    	<div class="col-md-9">
    		<div class="input-group input-medium date datepicker"><?php echo $form['closed_to'] ?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>
    	</div>
    </div>
    
  </div>
  
  <div class="tab-pane fade" id="tab_projects_filters">
  
    <div class="row">
  		<div class="col-md-6">
        <?php echo app::getReportFormFilterByTable('Status','user_reports[projects_status_id]','ProjectsStatus',$form['projects_status_id']->getValue()) ?>
        <?php echo app::getReportFormFilterByTable('Type','user_reports[projects_type_id]','ProjectsTypes',$form['projects_type_id']->getValue()) ?>      
      </div>
      <div class="col-md-6">
        <?php
   
          if(count($choices = app::getProjectChoicesByUser($sf_user,true,'tasks'))>0)
          { 
            if(!is_string($v = $form['projects_id']->getValue())) $v = '';
            
            echo  '
            <div class="form-group">
            	<label class="col-md-3 control-label">' . __('Projects') . '</label>
            	<div class="col-md-9">          		              
                ' . select_tag('user_reports[projects_id]',explode(',',$v),array('choices'=>$choices,'multiple'=>true),array('class'=>'form-control  multiple-select-tag','style'=>''))  . '
            	</div>
            </div>
          ';  
                      
          }
  
        ?>  
      </div>
    </div>
      
  </div>
</div>  

     
  </div>
</div>

<?php echo ajax_modal_template_footer() ?>
</form>

<?php include_partial('global/formValidator',array('form_id'=>'tasksReports')); ?>
