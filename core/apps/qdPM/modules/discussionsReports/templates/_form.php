<?php
/**
* WORK SMART
*/
?>

<?php if($form->getObject()->isNew()) $form->setDefault('users_id',$sf_user->getAttribute('id')) ?>

<form class="form-horizontal" role="form"  id="discussionsReports" action="<?php echo url_for('discussionsReports/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
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
    <a href="#tab_discussions_filters" data-toggle="tab"><?php echo __('Discussions Filters') ?></a>
  </li>        	
  <li>
    <a href="#tab_projects_filters" data-toggle="tab"><?php echo __('Projects Filters') ?></a>
  </li>
</ul>  
        
<div class="tab-content" >
  <div class="tab-pane fade active in" id="tab_discussions_filters">
    <?php echo app::getReportFormFilterByTable('Status','discussions_reports[discussions_status_id]','DiscussionsStatus',$form['discussions_status_id']->getValue()) ?>
  </div>
  <div class="tab-pane fade" id="tab_projects_filters">
  
    <?php echo app::getReportFormFilterByTable('Status','discussions_reports[projects_status_id]','ProjectsStatus',$form['projects_status_id']->getValue()) ?>
    <?php echo app::getReportFormFilterByTable('Type','discussions_reports[projects_type_id]','ProjectsTypes',$form['projects_type_id']->getValue()) ?>
    
    <?php
      if(count($choices = app::getProjectChoicesByUser($sf_user,true,'discussions'))>0)
      { 
        if(!is_string($v = $form['projects_id']->getValue())) $v = '';
        
        echo  '
        <div class="form-group">
        	<label class="col-md-3 control-label">' . __('Projects') . '</label>
        	<div class="col-md-9">          		              
            ' . select_tag('discussions_reports[projects_id]',explode(',',$v),array('choices'=>$choices,'multiple'=>true),array('class'=>'form-control multiple-select-tag','style'=>''))  . '
        	</div>
        </div>
      ';  
                  
      }
    ?>
  
  </div>  
</div>  
        
  </div>
</div>

<?php echo ajax_modal_template_footer() ?>

</form>

<?php include_partial('global/formValidator',array('form_id'=>'discussionsReports')); ?>

