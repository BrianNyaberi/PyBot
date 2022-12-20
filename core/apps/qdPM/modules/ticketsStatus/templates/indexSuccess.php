<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Tickets Status') ?></h3>

<?php
$lc = new cfgListingController($sf_context->getModuleName());
echo $lc->insert_button() . ' ' .  $lc->sort_button();
?>


<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>
      <th><?php echo __('Group') ?></th>
      <th width="100%"><?php echo __('Name') ?></th>            
      <th><?php echo __('Default?') ?></th>            
      <th><?php echo __('Sort Order') ?></th>
      <th><?php echo __('Active?') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets_statuss as $tickets_status): ?>
    <tr>
      <td><?php echo $lc->action_buttons($tickets_status->getId()) ?></td>
      <td><?php echo __($tickets_status->getGroup()) ?></td>
      <td><?php echo $tickets_status->getName() ?></td>
      <td><?php echo renderBooleanValue($tickets_status->getDefaultValue()) ?></td>            
      <td><?php echo $tickets_status->getSortOrder() ?></td>
      <td><?php echo renderBooleanValue($tickets_status->getActive()) ?></td>      
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($tickets_statuss)==0) echo '<tr><td colspan="7">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>

<?php echo $lc->insert_button(); ?>


