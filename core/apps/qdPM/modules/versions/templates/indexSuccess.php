<?php
/**
* WORK SMART
*/
?>
<?php include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<h3 class="page-title"><?php echo __('Tasks Versions') ?></h3>

<?php
$in_listing = array();
$in_listing['status'] = app::countItemsByTable('VersionsStatus');

$lc = new cfgListingController($sf_context->getModuleName(),'projects_id=' . $projects->getId());
echo $lc->insert_button(__('Add Version'));
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
      <th><?php echo __('Description') ?></th>
      <th><?php echo __('Due Date') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($versionss as $versions): ?>
    <tr>
      <td><?php echo $lc->action_buttons($versions->getId()) ?></td>      
      
      <?php if($in_listing['status']): ?>
      <td><?php echo app::getObjectName($versions->getVersionsStatus()) ?></td>
      <?php endif ?>
      
      <td><?php echo $versions->getName() ?></td>
      <td><?php echo $versions->getDescription() ?></td>
      <td><?php echo app::dateFormat($versions->getDueDate()) ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($versionss)==0) echo '<tr><td colspan="5">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>
<?php echo $lc->insert_button(__('Add Version')); ?>


