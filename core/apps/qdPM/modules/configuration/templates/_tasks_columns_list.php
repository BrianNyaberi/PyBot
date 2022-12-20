<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title""><?php echo __('Tasks Listing') ?></h3>
<br>
<div><?php echo __('Select tasks fields which will be display in tasks listing by default.'); ?></div>
<br>

<div class="checkbox-list">
  <?php echo select_tag('cfg[app_tasks_columns_list]',explode(',',sfConfig::get('app_tasks_columns_list')),array('choices'=>Tasks::getTasksColumnChoices(),'expanded'=>true,'multiple'=>true)) ?>
</div>
    
