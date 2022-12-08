<?php
/**
*qdPM
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@qdPM.net so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade qdPM to newer
* versions in the future. If you wish to customize qdPM for your
* needs please refer to http://www.qdPM.net for more information.
*
* @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
