<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Tickets Types') ?></h3>
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
      <th><?php echo __('Sort Order') ?></th>
      <th><?php echo __('Active?') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets_typess as $tickets_types): ?>
    <tr>
      <td><?php echo $lc->action_buttons($tickets_types->getId()) ?></td>
      <td><?php echo $tickets_types->getName() ?></td>                  
      <td><?php echo $tickets_types->getSortOrder() ?></td>      
      <td><?php echo renderBooleanValue($tickets_types->getActive()) ?></td> 
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($tickets_typess)==0) echo '<tr><td colspan="6">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>

<?php echo $lc->insert_button(); ?>

