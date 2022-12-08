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
