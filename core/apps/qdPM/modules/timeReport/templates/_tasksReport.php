<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Time Log') ?></h3>


<div><?php echo __('Export') .': <a href="#" onClick="time_report_export(\'time_log_export\',\'csv\');">.csv</a> | <a href="#" onClick="time_report_export(\'time_log_export\', \'txt\');">.txt</a>' ?></div>

  <div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
    <tr>
      <th><?php echo __('Date'); ?></th>
      <th><?php echo __('User'); ?></th>
      <th><?php echo __('Work Hours'); ?></th>
      <th><?php echo __('Task'); ?></th>
      <th><?php echo __('Task Status'); ?></th>
      <th><?php echo __('Project'); ?></th>
    </tr>
  <?php 
  
  $export = array();
  
  $export[] = array(__('Date'), 
                    __('User'),
                    __('Worked Hours'),
                    __('Task'),
                    __('Task Status'),
                    __('Project'),
                    __('Task Url'),                        
                   );
  
  $current_comment_date = '';
  
  $total_worked_hours_per_day = 0;
  $overall_total_worked_hours = 0;
    
  $tasks_id_list = array();
  $tasks_work_hours = array();
  
  foreach($tasks_comments as $comments):
    
  $tasks_id_list[] = $comments->getTasks()->getId();
  
  if(!isset($tasks_work_hours[$comments->getTasksId()]))
  {     
    $tasks_work_hours[$comments->getTasksId()] = $comments->getWorkedHours();       
  } 
  else
  {
    $tasks_work_hours[$comments->getTasksId()] += $comments->getWorkedHours();
  } 
  
  if($current_comment_date=='')
  {
    echo '
     <tr>
      <td><b>' . app::dateFormat($comments->getCreatedAt()) . '</b></td>
      <td colspan="5"></td>
     </tr>
    ';    
        
    $export[] = array(app::dateFormat($comments->getCreatedAt()),'','','','','','');    
  }
  elseif($current_comment_date!=app::dateFormat($comments->getCreatedAt()))
  {
    echo '
     <tr style="background: #f2f4f4">
      <td></td>
      <td ></td>      
      <td><b>' . $total_worked_hours_per_day . '</b></td>
      <td></td>
      <td></td>
      <td></td>
     </tr> 
     <tr>
      <td style="height: 10px; background: #f2f4f4" colspan="6"></td>
     </tr> 
     <tr>
      <td><b>' . app::dateFormat($comments->getCreatedAt()) . '</b></td>
      <td colspan="5"></td>
     </tr>
    ';
    
    $export[] = array('','',$total_worked_hours_per_day,'','','','');
    $export[] = array('','','','','','','');
    $export[] = array(app::dateFormat($comments->getCreatedAt()),'','','','','','');
    
    $total_worked_hours_per_day = 0;
  }
      
  $current_comment_date = app::dateFormat($comments->getCreatedAt());
  
  $total_worked_hours_per_day+=$comments->getWorkedHours();
  $overall_total_worked_hours+=$comments->getWorkedHours();
  ?>
    <tr>
      <td><?php echo app::dateTimeFormat($comments->getCreatedAt()); ?></td>
      <td><?php echo $comments->getUsers()->getName(); ?></td>
      <td><?php echo $comments->getWorkedHours(); ?></td>
      <td><?php echo link_to($comments->getTasks()->getName(),'tasksComments/index?tasks_id=' . $comments->getTasks()->getId() . '&projects_id=' . $comments->getTasks()->getProjects()->getId(),array('class'=>'jt','rel'=>url_for('tasks/info?projects_id=' . $comments->getTasks()->getProjects()->getId() . '&id=' . $comments->getTasks()->getId()),'title'=>__('Task Info'),'target'=>'new')); ?></td>
      <td><?php echo ($comments->getTasks()->getTasksStatus() ? $comments->getTasks()->getTasksStatus()->getName():''); ?></td>
      <td><?php echo link_to($comments->getTasks()->getProjects()->getName(),'projectsComments/index?projects_id=' . $comments->getTasks()->getProjects()->getId(),array('rel'=>url_for('projects/info?id=' . $comments->getTasks()->getProjects()->getId()),'title'=>__('Project Info'),'target'=>'new')); ?></td>
    </tr>
  <?php 
  
  $export[] = array(app::dateTimeFormat($comments->getCreatedAt()),
                        $comments->getUsers()->getName(),
                        $comments->getWorkedHours(),
                        $comments->getTasks()->getName(),
                        ($comments->getTasks()->getTasksStatus() ? $comments->getTasks()->getTasksStatus()->getName() :''),
                        $comments->getTasks()->getProjects()->getName(),
                        url_for('tasksComments/index?tasks_id=' . $comments->getTasks()->getId() . '&projects_id=' . $comments->getTasks()->getProjects()->getId(), true),
                                                
                       );
  
  endforeach; 
  
  echo '
     <tr>
      <td></td>
      <td></td>
      <td><b>' . $total_worked_hours_per_day . '</b></td>
      <td></td>
      <td></td>
      <td></td>
     </tr>';
     
  $export[] = array('','',$total_worked_hours_per_day,'','','','');   
     
  $export[] = array('','','','','','','');
  ?>
  </table>
</div>


<form action="<?php echo url_for('timeReport/export')?>" method="post" id="time_log_export">
  <?php echo input_hidden_tag('filename','time_log').input_hidden_tag('format').input_hidden_tag('export',json_encode($export)) ?>
</form> 

<?php 

  $tasks_id_list = array_unique($tasks_id_list);
  if(sizeof($tasks_id_list)>0):     
?>


<h3 class="page-title"><?php echo __('Tasks') ?></h3>

<div><?php echo __('Export') .': <a href="#" onClick="time_report_export(\'tasks_export\',\'csv\');">.csv</a> | <a href="#" onClick="time_report_export(\'tasks_export\', \'txt\');">.txt</a>' ?></div>

  <div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th><div><?php echo __('Projects'); ?></div></th>
        <th><div><?php echo __('Tasks'); ?></div></th>
        <th><div><?php echo __('Assigned To'); ?></div></th>
        <th><div><?php echo __('Work Hours'); ?></div></th>
        <th><div><?php echo __('Allocated'); ?></div></th>    
        <th><div><?php echo __('Discrepancy'); ?></div></th>        
        <th><div><?php echo __('Tasks Status'); ?></div></th>      
      </tr>
    </thead>  
<?php
  $export = array();
  $export[] = array(__('Projects'),__('Tasks'),__('Assigned To'),__('Work Hours'),__('Allocated'),__('Discrepancy'),__('Tasks Status'),__('Tasks Url'));
  
  
  $tasks_list = Doctrine_Core::getTable('Tasks')->createQuery('t')                                                    
          ->whereIn('t.id',array_unique($tasks_id_list))
          ->orderBy('t.name')          
          ->execute();
  
  $total_est_time = 0;
  $total_actual_time = 0;  
  
  foreach($tasks_list as $tasks):
  
   
  $est_time = $tasks->getEstimatedTime();
  $actual_time  = $tasks_work_hours[$tasks->getId()]; 
  $actual_time_diff_str = '';
            
  $total_est_time += $est_time;
  $total_actual_time += $actual_time;
    
  $actual_time_diff = $actual_time-$est_time;
    
  
  if($est_time-$actual_time==0)
  {
    $actual_time_diff_str =  '0';
  }
  elseif($est_time-$actual_time>0)
  {
    $actual_time_diff_str =  '<font color="#32602f">+' . ($est_time-$actual_time) . '</font>';
  }
  elseif($est_time-$actual_time<0)
  {
    $actual_time_diff_str =  '<font color="#a23343">' . ($est_time-$actual_time) . '</font>'; 
  }
  
                        
  $assignedToList = Users::getNameById($tasks->getAssignedTo());
                       
?>
  <tr>
    <td><?php echo link_to($tasks->getProjects()->getName(),'projectsComments/index?projects_id=' . $tasks->getProjects()->getId(),array('rel'=>url_for('projects/info?id=' . $tasks->getProjects()->getId()),'title'=>__('Project Infor'),'target'=>'new')); ?></td>
    <td><?php echo link_to($tasks->getName(),'tasksComments/index?tasks_id=' . $tasks->getId() . '&projects_id=' . $tasks->getProjects()->getId(),array('class'=>'jt','rel'=>url_for('tasks/info?projects_id=' . $tasks->getProjects()->getId() . '&id=' . $tasks->getId()),'title'=>__('Task Info'),'target'=>'new')); ?></td>
    <td><?php echo $assignedToList; ?></td>
    <td><?php echo $actual_time; ?></td>
    <td><?php echo $est_time; ?></td>
    <td><?php echo $actual_time_diff_str; ?></td>
        
    <td><?php echo ($tasks->getTasksStatus() ? $tasks->getTasksStatus()->getName():''); ?></td>
  </tr>    
  
<?php  


   $export[] = array($tasks->getProjects()->getName(),$tasks->getName(),str_replace('<br>',', ',$assignedToList),$actual_time,$est_time,strip_tags($actual_time_diff_str),($tasks->getTasksStatus() ? $tasks->getTasksStatus()->getName():''),url_for('tasksComments/index?tasks_id=' . $tasks->getId() . '&projects_id=' . $tasks->getProjects()->getId(),true));

  endforeach;
  
  
  if($total_est_time-$total_actual_time==0)
  {
    $actual_time_diff_str =  '0';
  }
  elseif($total_est_time-$total_actual_time>0)
  {
    $actual_time_diff_str =  '<font color="#32602f">+' . ($total_est_time-$total_actual_time) . '</font>';
  }
  elseif($total_est_time-$total_actual_time<0)
  {
    $actual_time_diff_str =  '<font color="#a23343">' . ($total_est_time-$total_actual_time) . '</font>'; 
  }
  
  echo '
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td><b>' . $total_actual_time . '</b></td>
      <td><b>' . $total_est_time . '</b></td>
      <td><b>' . $actual_time_diff_str . '</b></td>            
      <td></td>
    </td>
  </tfoot>
  ';
  
?>
  </table>
</div>  
      
<?php 
  $export[] = array('','','',$total_actual_time,$total_est_time,strip_tags($actual_time_diff_str),'','');

endif; 
    
?>

<form action="<?php echo url_for('timeReport/export')?>" method="post" id="tasks_export">
  <?php 
    echo input_hidden_tag('filename','tasks').input_hidden_tag('format') .input_hidden_tag('export',json_encode($export))
    
  ?>
</form> 

 
