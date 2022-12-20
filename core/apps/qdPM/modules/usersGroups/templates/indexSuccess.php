<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Users Groups') ?></h3>
<?php
$lc = new cfgListingController($sf_context->getModuleName());
echo $lc->insert_button();
?>

<div class="table-scrollable">
<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th widht="100"><?php echo __('Action') ?></th>
      
      <th ><?php echo __('Name') ?></th>
      <th><?php echo __('Projects') ?></th>
      <th><?php echo __('Tasks') ?></th>            
      <th><?php echo __('Tickets') ?></th>
      <th><?php echo __('Discussions') ?></th>      
      <th><?php echo __('Extra') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users_groupss as $users_groups): ?>
    <tr>
      <td><?php echo $lc->delete_mbutton($users_groups->getId())  . ' ' . $lc->edit_button($users_groups->getId()) ?></td>      
      <td><?php echo '<b>' . $users_groups->getName()  . '</b>' .  
                     '<br><span style="font-size:11px;"> - ' . __('Assigned Users') . ': ' . UsersGroups::countUsersByGroupId($users_groups->getId()) . '</span>' ?></td>
      <td><?php echo UsersGroups::getAccessTable($users_groups,'projects') ?></td>      
      <td><?php echo UsersGroups::getAccessTable($users_groups,'tasks') ?></td>
      <td><?php echo UsersGroups::getAccessTable($users_groups,'tickets') ?></td>
      <td><?php echo UsersGroups::getAccessTable($users_groups,'discussions') ?></td>      
      <td><?php echo UsersGroups::getAccessTable($users_groups,'extra') ?></td>
      
    </tr>
    <?php endforeach; ?>
    <?php if(sizeof($users_groupss)==0) echo '<tr><td colspan="7">' . __('No Records Found') . '</td></tr>';?>
  </tbody>
</table>
</div>

<?php echo $lc->insert_button(); ?>


