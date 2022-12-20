<?php
/**
* WORK SMART
*/
?>
<?php if($sf_request->getParameter('projects_id')>0) include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<h3 class="page-title"><?php echo __('Discussions') ?></h3>

<div><?php if(!$sf_request->hasParameter('search')) include_component('discussions','filtersPreview') ?></div>

<?php include_component('discussions','listing') ?>
