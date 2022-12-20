<?php
/**
* WORK SMART
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
