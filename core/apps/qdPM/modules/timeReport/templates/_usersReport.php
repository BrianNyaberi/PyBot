<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Users') ?></h3>

<div><?php echo __('Export') .': <a href="#" onClick="time_report_export(\'users_export\',\'csv\');">.csv</a> | <a href="#" onClick="time_report_export(\'users_export\', \'txt\');">.txt</a>' ?></div>

<?php
  $export = array();
  $users_work_hours = array();
  $users_tasks_list = array();
  $users_ids = array();
  
  foreach($tasks_comments as $comments)
  { 
    if(!isset($users_work_hours[$comments->getCreatedBy()]))
    {     
      $users_work_hours[$comments->getCreatedBy()] = $comments->getWorkedHours();       
    } 
    else
    {
      $users_work_hours[$comments->getCreatedBy()] += $comments->getWorkedHours();
    } 
    
    $users_tasks_list[$comments->getCreatedBy()][] = $comments->getTasksId();
    
    $users_ids[] = $comments->getCreatedBy();   
  }
  
  $users_ids = array_unique($users_ids);
    
  $count_items = 0;
?>
  <div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>    
        <th><div><?php echo __('User'); ?></div></th>
        <th><div><?php echo __('Work Hours'); ?></div></th>
        <th><div><?php echo __('Allocated'); ?></div></th>
        <th><div><?php echo __('Discrepancy'); ?></div></th>      
      </tr> 
    </thead>
    <?php 
    
    $export[] = array(__('User'),__('Work Hours'),__('Allocated'),__('Discrepancy'));
    
    $total_work_hours = 0;
    $total_allocated = 0;
        
    $users_list = Doctrine_Core::getTable('Users')
      ->createQuery('u')->leftJoin('u.UsersGroups ug')
      ->whereIn('u.id',$users_ids)
      ->orderBy('ug.name, u.name')
      ->execute();
    
    foreach($users_list as $user){
    
    if(!isset($users_work_hours[$user->getId()])) continue;
    
    $work_hours = $users_work_hours[$user->getId()];
    $allocated = 0;
    
    $tasks = Doctrine_Core::getTable('Tasks')->createQuery('t')                 
          ->addSelect('sum(estimated_time) as sum_estimated_time')                         
          ->whereIn('t.id',array_unique($users_tasks_list[$user->getId()]))          
          ->fetchOne();
    $allocated = (float)$tasks->getSumEstimatedTime();
                      
    $filterByDiscrepancy = '';
    if(isset($filter_by['TimeDiscrepancy']))$filterByDiscrepancy  = $filter_by['TimeDiscrepancy'];
    
    if($filterByDiscrepancy=='ok' and $allocated-$work_hours!=0) continue;
    if($filterByDiscrepancy=='under' and $allocated-$work_hours>=0) continue;
    if($filterByDiscrepancy=='over' and $allocated-$work_hours<=0) continue;
    
    $total_work_hours += $work_hours;
    $total_allocated += $allocated;
    
    ?>
     <tr onClick="view_user_time_report(<?php echo $user->getId(); ?>)" style="cursor: pointer;">
       <td><?php echo link_to($user->getName(),'timeReport/' . $sf_context->getActionName()  ,array('query_string' => 'filter_by[CommentCreatedBy]=' . $user->getId()  . ($sf_request->hasParameter('projects_id')?'&projects_id=' . $sf_request->getParameter('projects_id'):''))) ?></td>
       <td><?php echo $work_hours ?></td>
       <td><?php echo $allocated ?></td>
       <td><?php  
       
          $discrepancy = $allocated-$work_hours;
          
          if($discrepancy>0)
          {            
            $discrepancy =  '<font color="#32602f">+' . $discrepancy . '</font>';
          }
          elseif($discrepancy<0)
          {
            $discrepancy =  '<font color="#a23343">' . $discrepancy . '</font>'; 
          }
          
          echo $discrepancy;
          
       ?></td>
     </tr> 
    <?php 
          $count_items++;
          
      $export[] = array($user->getName(),$work_hours,$allocated,strip_tags($discrepancy));     
    }
        
      if($count_items==0)
      {
        echo '<td colspan="4">' . __('No records found') . '</td>';
      } 
      else
      {
        if($total_allocated-$total_work_hours==0)
        {
          $discrepancy =  '0';
        }
        elseif($total_allocated-$total_work_hours>0)
        {
          $discrepancy = '<font color="#32602f">+' . ($total_allocated-$total_work_hours) . '</font>';
        }
        elseif($total_allocated-$total_work_hours<0)
        {
          $discrepancy = '<font color="#a23343">' . ($total_allocated-$total_work_hours) . '</font>'; 
        }
        
        echo '
        <tfoot>
          <tr>
            <td></td>          
            <td><b>' . $total_work_hours . '</b></td>
            <td><b>' . $total_allocated . '</b></td>
            <td><b>' . $discrepancy . '</b></td>          
          </td>
        </tfoot>
        ';
        
        $export[] = array('',$total_work_hours,$total_allocated,strip_tags($discrepancy));
      } 
    ?>
   </table>
  </div> 
   
<form action="<?php echo url_for('timeReport/export')?>" method="post" id="users_export">
  <?php 
        echo input_hidden_tag('filename','users').input_hidden_tag('format') . input_hidden_tag('export',json_encode($export));
        
    ?>
</form>   
   
  
     
