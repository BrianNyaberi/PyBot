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

<?php if($form->getObject()->isNew())$form->setDefault('created_by',$sf_user->getAttribute('id')) ?>

<form class="form-horizontal" role="form"  id="projects" action="<?php echo url_for('projects/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php print 'enctype="multipart/form-data" ' ?>>
<div class="modal-body">
  <div class="form-body">
  
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>

<?php echo $form->renderHiddenFields(false) ?>          
<?php echo input_hidden_tag('redirect_to',$sf_request->getParameter('redirect_to')) ?>

<?php echo $form->renderGlobalErrors() ?>

               
<ul class="nav nav-tabs">
	<li class="active">
    <a href="#tab_general" data-toggle="tab"><?php echo __('General') ?></a>
  </li>
	<li>
    <a href="#tab_team" data-toggle="tab"><?php echo __('Team') ?></a>
  </li>        	
  <li>
    <a href="#tab_attachments" data-toggle="tab"><?php echo __('Attachments') ?></a>
  </li>
</ul>


<div class="tab-content" >
    <div class="tab-pane fade active in" id="tab_general">
          
      <?php if(app::countItemsByTable('ProjectsTypes')>0): ?>    
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['projects_types_id']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['projects_types_id'] ?>
      	</div>
      </div> 
      <?php endif ?>     
      
      <?php if(app::countItemsByTable('ProjectsStatus')>0): ?>
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['projects_status_id']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['projects_status_id'] ?>
      	</div>
      </div>
      <?php endif ?> 
      
      <div class="form-group">
      	<label class="col-md-3 control-label"><span class="required">*</span> <?php echo $form['name']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['name'] ?>
      	</div>
      </div>  
      
      <?php echo ExtraFieldsList::renderFormFiledsByType('projects',$form->getObject(),$sf_user,'input')?>
      
      <div class="form-group">
      	<label class="col-md-3 control-label"> <?php echo $form['description']->renderLabel() ?></label>
      	<div class="col-md-9">
      		<?php echo $form['description'] ?>
      	</div>
      </div> 
      
      <?php echo ExtraFieldsList::renderFormFiledsByType('projects',$form->getObject(),$sf_user,'text')?>          
      <?php echo ExtraFieldsList::renderFormFiledsByType('projects',$form->getObject(),$sf_user,'file')?>
          
        </div>                
        
        <div class="tab-pane fade" id="tab_team">
          <?php include_component('projects','team',array('project'=>$form->getObject())) ?>
        </div>
                                      
        <div class="tab-pane fade" id="tab_attachments"> 
          <?php include_component('attachments','attachments',array('bind_type'=>'projects','bind_id'=>($form->getObject()->isNew()?0:$form->getObject()->getId()))) ?>
        </div>
                
      </div>

<?php include_partial('global/formValidatorExt',array('form_id'=>'projects')); ?>
      
  </div>
</div>

<?php echo ajax_modal_template_footer() ?>

</form>



<script type="text/javascript">    
  $(function() {                                                                                                                                                              
    $('.check_all_users').bind('click', function() { 
       rnd = $(this).attr('id').replace('check_all_users_','');
       checked = $(this).attr('checked');
       
       $( ".rnd"+rnd ).each(function() {
          if(checked)
          {            
            set_checkbox_checked($(this).attr('id'),true)
          }
          else
          {            
            set_checkbox_checked($(this).attr('id'),false)
          }
          
          updateUserRoles($(this));
       });
    });
                                                              
  });
</script> 


