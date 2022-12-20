<?php
/**
* WORK SMART
*/
?>
<?php if(Users::hasAccess('view','tasks',$sf_user,$sf_request->getParameter('projects_id'))) include_component('tasks','relatedTasksToDiscussions',array('discussions_id'=>$discussions->getId(),'is_email'=>isset($is_email))) ?>


<div class="table-scrollable">
<table class="table table-bordered table-hover table-item-details">
  <tr>
    <th><?php echo __('Id') ?>:</th>
    <td><?php echo $discussions->getId() ?></td>
  </tr>
    
  <?php if($discussions->getDiscussionsStatusId()>0) echo '<tr><th>' . __('Status') . ':</th><td>' . $discussions->getDiscussionsStatus()->getName() . '</td></tr>';?>
      
  <?php echo ExtraFieldsList::renderInfoFileds('discussions',$discussions,$sf_user) ?>
  
  <tr>
    <th><?php echo __('Assigned To') ?>:</th>
    <td>
<?php
  foreach(explode(',',$discussions->getAssignedTo()) as $users_id)
  {
    if($user = Doctrine_Core::getTable('Users')->find($users_id))
    {
      echo renderUserPhoto($user->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' . $user->getName()  . '<br>';
    }
  }
  
  if(strlen($discussions->getAssignedTo())==0) echo  __('No Assigned Users');
?>    
    </td>
  </tr> 
  
  <tr>
    <th><?php echo __('Created By') ?>:</th>
    <td>
<?php
  if($discussions->getUsersId()>0)
  {
    echo renderUserPhoto($discussions->getUsers()->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' . $discussions->getUsers()->getName();
  }   
?>         
    </td>
  </tr> 
               
</table>
</div>
