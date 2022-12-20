<?php
/**
* WORK SMART
*/
?>
<?php if($sf_request->hasParameter('projects_id')) include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<h3 class="page-title"><?php echo __('Tasks') ?></h3>

<div><?php if(!$sf_request->hasParameter('search')) include_component('tasks','filtersPreview') ?></div>

<?php 
  include_component('tasks','listing');
?>
