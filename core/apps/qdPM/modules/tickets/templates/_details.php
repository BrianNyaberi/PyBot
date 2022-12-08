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
<?php if(Users::hasAccess('view','tasks',$sf_user,$sf_request->getParameter('projects_id'))) include_component('tasks','relatedTasksToTickets',array('tickets_id'=>$tickets->getId(),'is_email'=>isset($is_email))) ?>

<div class="table-scrollable">
<table class="table table-bordered table-hover table-item-details">
  <tr>
    <th><?php echo __('Id') ?>:</th>
    <td><?php echo $tickets->getId() ?></td>
  </tr>
  
  <tr>
    <th><?php echo __('Department') ?>:</th>
    <td><?php echo $tickets->getDepartments()->getName() ?>
<?php
  if($tickets->getDepartments_id()>0)
  {
    echo '<br>' . renderUserPhoto($tickets->getDepartments()->getUsers()->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' .  $tickets->getDepartments()->getUsers()->getName();
  }
?> 
    </td>
  </tr>
  
  <?php if($tickets->getTicketsStatusId()>0) echo '<tr><th>' . __('Status') . ':</th><td>' . $tickets->getTicketsStatus()->getName() . '</td></tr>';?>    
  <?php if($tickets->getTicketsTypesId()>0) echo '<tr><th>' . __('Type') . ':</th><td>' . $tickets->getTicketsTypes()->getName() . '</td></tr>';?>
      
  <?php echo ExtraFieldsList::renderInfoFileds('tickets',$tickets,$sf_user) ?>
            
  <tr>
    <th><?php echo __('Created At') ?>:</th>
    <td><?php echo app::dateTimeFormat($tickets->getCreatedAt()) ?></td>
  </tr> 
  <tr>
    <th><?php echo __('Created By') ?>:</th>
    <td>
<?php
  if($tickets->getUsersId()>0)
  {
    echo renderUserPhoto($tickets->getUsers()->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' '. $tickets->getUsers()->getName() ;
  }
  else
  {
    echo  $tickets->getUserName()  . '<br>' . $tickets->getUserEmail() ;
  }   
?>     
    </td>
  </tr>   
</table>
</div>
