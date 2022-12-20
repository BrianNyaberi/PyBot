<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Default Phases') ?></h3>

<?php
$lc = new cfgListingController($sf_context->getModuleName());
echo $lc->insert_button();
?>

<div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>
      <th width="100%"><?php echo __('Name') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($phasess as $phases): ?>
    <tr>
      <td><?php echo $lc->action_buttons($phases->getId()) ?></td>
      <td><?php echo $phases->getName() ?></td>      
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>

<?php echo $lc->insert_button(); ?>
