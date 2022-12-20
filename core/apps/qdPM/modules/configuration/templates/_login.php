<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Login Page Configuration') ?></h1>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_login_page_heading"><?php echo __('Heading'); ?></label>
  <div class="col-md-9">	
	  <?php echo input_tag('cfg[app_login_page_heading]', sfConfig::get('app_login_page_heading'),array('class'=>'form-control')); ?> 
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_login_page_content"><?php echo __('Content'); ?></label>
  <div class="col-md-9">	
	  <?php echo textarea_tag('cfg[app_login_page_content]', sfConfig::get('app_login_page_content'),array('class'=>'form-control')); ?>
  </div>			
</div>

<div class="form-group">
	<label class="col-md-3 control-label" for="cfg_app_logo_file"><?php echo __('Background'); ?> (1920x1200)</label>
  <div class="col-md-9">	
<?php
    echo  input_file_tag('cfg_app_login_background_file')  . input_hidden_tag('cfg[app_login_background]', sfConfig::get('app_login_background')); 
      if(is_file(sfConfig::get('sf_upload_dir')  . '/' . sfConfig::get('app_login_background')))
      {
        echo '<div>' . sfConfig::get('app_login_background') . '</div>' . input_checkbox_tag('delete_login_background') . ' <label for="delete_login_background">' . __('Delete') . '</label>';
      }  
?>      

    <span class="help-block">
       (*.jpg, *.png)
    </span>
  </div>			
</div>



