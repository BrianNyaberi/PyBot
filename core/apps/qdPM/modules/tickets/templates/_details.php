<?php
/**
* WORK SMART
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
