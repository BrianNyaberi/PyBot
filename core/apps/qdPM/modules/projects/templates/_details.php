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

<div class="table-scrollable">
<table class="table table-bordered table-hover table-item-details">  
<tbody>
  <tr>
    <th><?php echo __('Id') ?>:</th>
    <td><?php echo $projects->getId() ?></td>
  </tr>
  
  <?php if($projects->getProjectsStatusId()>0) echo '<tr><th>' . __('Status') . ':</th><td>' . $projects->getProjectsStatus()->getName() . '</td></tr>';?>
  <?php if($projects->getProjectsTypesId()>0) echo '<tr><th>' . __('Type') . ':</th><td>' . $projects->getProjectsTypes()->getName() . '</td></tr>';?>
    
  <?php echo ExtraFieldsList::renderInfoFileds('projects',$projects,$sf_user) ?>
  
  
  <tr>
    <th><?php echo __('Team') ?>:</th>
    <td>
<?php
  foreach(explode(',',$projects->getTeam()) as $users_id)
  {
    if($user = Doctrine_Core::getTable('Users')->find($users_id))
    {
      echo  renderUserPhoto($user->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' . $user->getName()  . '<br>';
    }
  }
  if(strlen($projects->getTeam())==0) echo  __('No Assigned Users') ;
?>    
    
    </td>
  </tr> 
      
  <tr>
    <th><?php echo __('Created At') ?>:</th>
    <td><?php echo app::dateTimeFormat($projects->getCreatedAt()) ?></td>
  </tr>  
  <tr>
    <th><?php echo __('Created By') ?>:</th>
    <td><?php echo renderUserPhoto($projects->getUsers()->getPhoto(),array('width'=>'28','style'=>'width:28px; margin-bottom: 2px;'))  . ' ' .  $projects->getUsers()->getName() ?></td>
  </tr>  
</tbody>  
</table>
</div>
