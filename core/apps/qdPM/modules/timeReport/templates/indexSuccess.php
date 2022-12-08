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
<?php if($sf_request->hasParameter('projects_id')) include_component('projects','shortInfo', array('projects'=>$projects)) ?>

<h3 class="page-title"><?php echo __('Users Time Report') ?></h3>

<div class="pageHeading">
  <table>
    <tr>      
      <td style="padding-left: 15px;"><?php //include_component('timeReport','filters',array('action_name'=>$sf_context->getActionName())) ?></td>    
      <td style="padding-left: 10px;"><?php //include_component('timeReport','extraFilters',array('filter_by'=>$filter_by,'action_name'=>$sf_context->getActionName())) ?></td>
    </tr>  
  </table>
</div>

<div><?php include_component('timeReport','filtersPreview',array('action_name'=>$sf_context->getActionName())) ?></div>

<?php 

if(count($tasks_comments)>0)
{  

  include_partial('timeReport/usersReport',array('tasks_comments'=>$tasks_comments, 'filter_by'=>$filter_by));
    
  $show_tasks_report = false;
  
  if(isset($filter_by['CommentCreatedBy']))
  {
    if($filter_by['CommentCreatedBy']>0)$show_tasks_report = true;
  }
  
  if(isset($filter_by['Projects']))
  {
    if(count(explode(',',$filter_by['Projects']))==1)
    {
      $show_tasks_report = true;
    }
  }
  
  if($show_tasks_report)
  {
    include_partial('timeReport/tasksReport',array('tasks_comments'=>$tasks_comments,'filter_by'=>$filter_by));
  }
    
  if(!$sf_request->hasParameter('projects_id'))
  {
    include_partial('timeReport/projectsReport',array('tasks_comments'=>$tasks_comments, 'filter_by'=>$filter_by));
  }
}
else
{
  echo __('No Records Found');
}  

?>

<?php include_partial('global/jsTips'); ?>
