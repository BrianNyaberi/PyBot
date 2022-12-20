<?php
/**
* WORK SMART
*/
?>
<?php if($sf_request->hasParameter('projects_id')) include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<h3 class="page-title"><?php echo __('Tickets') ?></h3>

<div><?php if(!$sf_request->hasParameter('search')) include_component('tickets','filtersPreview') ?></div>

<?php include_component('tickets','listing') ?>
