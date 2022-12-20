<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Discussions Status') ?></h3>

<?php
$lc = new cfgListingController($sf_context->getModuleName());
echo $lc->insert_button() . ' ' .  $lc->sort_button();
?>

<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>      
      <th width="100%"><?php echo __('Name') ?></th>            
      <th><?php echo __('Default?') ?></th>      
      
      <th><?php echo __('Sort Order') ?></th>
      <th><?php echo __('Active?') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($discussions_statuss as $discussions_status): ?>
    <tr>
      <td><?php echo $lc->action_buttons($discussions_status->getId()) ?></td>      
      <td><?php echo $discussions_status->getName() ?></td>
      <td><?php echo renderBooleanValue($discussions_status->getDefaultValue()) ?></td>                  
      <td><?php echo $discussions_status->getSortOrder() ?></td>
      <td><?php echo renderBooleanValue($discussions_status->getActive()) ?></td>
      
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>

<?php echo $lc->insert_button(); ?>


