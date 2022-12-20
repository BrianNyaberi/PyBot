<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Projects') ?></h1>

<div><?php if(!$sf_request->hasParameter('search')) include_component('projects','filtersPreview') ?></div>

<?php include_component('projects','listing') ?>
