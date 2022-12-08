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
<?php if(count($tasks_list)>0): ?>

<div class="table-scrollable">
<table class="table table-bordered table-hover table-item-details-users">
 <thead> 
  <tr>
    <th><?php echo __('Related Tasks') ?></th>
  </tr>
</thead>
<tbody>
<?php
$status = array();   
foreach($tasks_list as $tasks): 
if($tasks['tasks_status_id']>0) $status[] = $tasks['tasks_status_id'];
?>
  <tr id="related_task_<?php echo $tasks['id'] ?>">
    <td><?php echo link_to((isset($tasks['TasksLabels'])?$tasks['TasksLabels']['name'] . ': ':'') . $tasks['name'] . (isset($tasks['TasksStatus']) ? ' [' . $tasks['TasksStatus']['name'] . ']':''), 'tasksComments/index?tasks_id=' . $tasks['id'] . '&projects_id=' . $tasks['projects_id'],array('absolute'=>true)) ?></td>
    <td style="text-align: right;"><?php if(!$is_email) echo '<a href="javascript: removeRelated(\'related_task_' . $tasks['id'] . '\',\'' . url_for('app/removeRelatedTicketWithTask?tasks_id=' . $tasks['id'] . '&tickets_id=' . $sf_request->getParameter('tickets_id')) . '\')" class="btn btn-default btn-xs purple" title="' . __('Delete Related') . '"><i class="fa fa-trash-o"></i></a>'  ?></td>
  </tr>  
      
<?php endforeach ?>
</tbody>
</table>
</div>

<?php endif ?>
 
