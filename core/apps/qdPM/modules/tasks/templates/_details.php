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
<?php if(Users::hasAccess('view','tickets',$sf_user,$sf_request->getParameter('projects_id'))) include_component('tickets','relatedTicketsToTasks',array('tasks_id'=>$tasks->getId(),'is_email'=>isset($is_email))) ?>
<?php if(Users::hasAccess('view','discussions',$sf_user,$sf_request->getParameter('projects_id'))) include_component('discussions','relatedDiscussionsToTasks',array('tasks_id'=>$tasks->getId(),'is_email'=>isset($is_email))) ?>

<div class="table-scrollable">
<table class="table table-bordered table-hover table-item-details">
  <tr>
    <th><?php echo __('Id') ?>:</th>
    <td><?php echo $tasks->getId() ?></td>
  </tr>
  <?php if($tasks->getTasksLabelId()>0) echo '<tr><th>' . __('Label') . ':</th><td>' . $tasks->getTasksLabels()->getName() . '</td></tr>';?>
  <?php if($tasks->getTasksStatusId()>0) echo '<tr><th>' . __('Status') . ':</th><td>' . $tasks->getTasksStatus()->getName() . '</td></tr>';?>
  <?php if($tasks->getClosedDate()) echo '<tr><th>' . __('Closed') . ':</th><td>' . app::dateTimeFormat($tasks->getClosedDate()) . '</td></tr>';?>
  <?php if($tasks->getTasksPriorityId()>0) echo '<tr><th>' . __('Priority') . ':</th><td>' . $tasks->getTasksPriority()->getName() . '</td></tr>';?>
  <?php if($tasks->getTasksTypeId()>0) echo '<tr><th>' . __('Type') . ':</th><td>' . $tasks->getTasksTypes()->getName() . '</td></tr>';?>
  <?php if($tasks->getTasksGroupsId()>0) echo '<tr><th>' . __('Group') . ':</th><td>' . $tasks->getTasksGroups()->getName() . '</td></tr>';?>
  <?php if($tasks->getProjectsPhasesId()>0) echo '<tr><th>' . __('Phase') . ':</th><td>' . $tasks->getProjectsPhases()->getName() . '</td></tr>';?>
  <?php if($tasks->getVersionsId()>0) echo '<tr><th>' . __('Version') . ':</th><td>' . $tasks->getVersions()->getName() . '</td></tr>';?>
    
  <?php echo ExtraFieldsList::renderInfoFileds('tasks',$tasks,$sf_user) ?>
  
  <?php if($tasks->getEstimatedTime()>0) echo '<tr><th>' . __('Estimated Time') . ':</th><td>' . $tasks->getEstimatedTime() . '</td></tr>';?>
  <?php if(($work_hours = TasksComments::getTotalWorkHours($tasks->getId()))>0){ $discrepancy = $tasks->getEstimatedTime()-$work_hours; echo '<tr><th>' . __('Work Hours') . ':</th><td>' . $work_hours  . ($discrepancy<0 ? ' <font color="#a23343">(' . $discrepancy . ')</font>': ($discrepancy>0 ?' <font color="#32602f">(+' . $discrepancy . ')</font>':'')) . '</td></tr>'; } ?>
    
  <?php if($tasks->getStartDate()) echo '<tr><th>' . __('Start Date') . ':</th><td>' . app::dateTimeFormat($tasks->getStartDate()) . '</td></tr>';?>
  <?php if($tasks->getDueDate()) echo '<tr><th>' . __('Due Date') . ':</th><td>' . app::dueDateFormat($tasks->getDueDate()) . '</td></tr>';?>
  <?php if($tasks->getProgress()>0) echo '<tr><th>' . __('Progress') . ':</th><td>' . $tasks->getProgress() . '%</td></tr>';?>
     
     
  <tr>
    <th><?php echo __('Assigned To') ?>:</th>
    <td>
<?php
  foreach(explode(',',$tasks->getAssignedTo()) as $users_id)
  {
    if($user = Doctrine_Core::getTable('Users')->find($users_id))
    {
      echo  renderUserPhoto($user->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' . $user->getName()  . '<br>';
    }
  }
  
  if(strlen($tasks->getAssignedTo())==0) echo  __('No Assigned Users') ;
?>      
    </td>
  </tr>      
      
  <tr>
    <th><?php echo __('Created At') ?>:</th>
    <td><?php echo app::dateTimeFormat($tasks->getCreatedAt()) ?></td>
  </tr>
  <tr>
    <th><?php echo __('Created By') ?>:</th>
    <td><?php echo renderUserPhoto($tasks->getUsers()->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' .  $tasks->getUsers()->getName() ?></td>
  </tr>   
</table>
</div>
