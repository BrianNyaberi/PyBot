<?php
/**
* WORK SMART
*/
?>
<?php include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<h3 class="page-title"><?php echo __('Projects Phases') ?></h3>

<?php
$in_listing = array();
$in_listing['status'] = app::countItemsByTable('PhasesStatus');

$lc = new cfgListingController($sf_context->getModuleName(),'projects_id=' . $projects->getId());
echo $lc->insert_button(__('Add Phase'));
?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th><?php echo __('Action') ?></th>
      
      <?php if($in_listing['status']): ?>
      <th><?php echo __('Status') ?></th>
      <?php endif ?>
      
      <th width="100%"><?php echo __('Name') ?></th>
      <th><?php echo __('Due Date') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($projects_phasess as $projects_phases): ?>
    <tr>
      <td><?php echo $lc->action_buttons($projects_phases->getId()) ?></td>
      
      
      <?php if($in_listing['status']): ?>
      <td><?php echo app::getObjectName($projects_phases->getPhasesStatus()) ?></td>
      <?php endif ?>
            
      <td><?php echo $projects_phases->getName() ?></td>
      <td><?php echo app::dateFormat($projects_phases->getDueDate()) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($projects_phasess)==0) echo '<tr><td colspan="4">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>
<?php echo $lc->insert_button(__('Add Phase')); ?>
