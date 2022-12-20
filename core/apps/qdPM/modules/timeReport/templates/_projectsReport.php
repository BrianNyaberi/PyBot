<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo __('Projects') ?></h3>

<div><?php echo __('Export') .': <a href="#" onClick="time_report_export(\'projects_export\',\'csv\');">.csv</a> | <a href="#" onClick="time_report_export(\'projects_export\', \'txt\');">.txt</a>' ?></div>

<?php
  $export = array();
  $projects_work_hours = array();
  $projects_taks_list = array();
  $projects_ids = array();
  foreach($tasks_comments as $comments)
  { 
    if(!isset($projects_work_hours[$comments->getTasks()->getProjectsId()]))
    {     
      $projects_work_hours[$comments->getTasks()->getProjectsId()] = $comments->getWorkedHours();       
    } 
    else
    {
      $projects_work_hours[$comments->getTasks()->getProjectsId()] += $comments->getWorkedHours();
    } 
    
    $projects_taks_list[$comments->getTasks()->getProjectsId()][] = $comments->getTasksId();
    
    $projects_ids[] = $comments->getTasks()->getProjectsId();   
  }
  
  $projects_ids =array_unique($projects_ids);
      
    
  $count_items = 0;
?>
  <div class="table-scrollable">
	<table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>    
        <th><div><?php echo __('Project'); ?></div></th>
        <th><div><?php echo __('Work Hours'); ?></div></th>
        <th><div><?php echo __('Allocated'); ?></div></th>
        <th><div><?php echo __('Discrepancy'); ?></div></th>      
      </tr> 
    </thead>
    <?php
    
    $export[] = array(__('Project'),__('Work Hours'),__('Allocated'),__('Discrepancy'));
    
    $total_work_hours = 0;
    $total_allocated = 0;
     
    $projects_list = Doctrine_Core::getTable('Projects')->createQuery('p')          
          ->leftJoin('p.ProjectsStatus ps')
          ->leftJoin('p.ProjectsTypes pt')          
          ->leftJoin('p.Users')          
          ->whereIn('p.id',$projects_ids)
          ->orderBy('p.name')
          ->execute();    
    
    
    foreach($projects_list as $project){
                
    $work_hours = $projects_work_hours[$project->getId()];
    $allocated = 0;
        
    $tasks = Doctrine_Core::getTable('Tasks')->createQuery('t')                 
          ->addSelect('sum(estimated_time) as sum_estimated_time')                         
          ->whereIn('t.id',array_unique($projects_taks_list[$project->getId()]))          
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
     <tr onClick="view_project_time_report(<?php echo $project->getId(); ?>)" style="cursor: pointer;">
       <td><?php echo link_to($project->getName(),'timeReport/' . $sf_context->getActionName(),array('title'=>__('Project Info'),'rel'=>url_for('projects/info?id=' . $project->getId()),'query_string'=>'filter_by[Projects]=' . $project->getId())) ?></td>
       <td><?php echo $work_hours ?></td>
       <td><?php echo $allocated ?></td>
       <td><?php  
       
          $discrepancy = $allocated-$work_hours;
          
          if($discrepancy>0)
          {
            $discrepancy = '<font color="#32602f">+' . ($allocated-$work_hours) . '</font>';
          }
          elseif($discrepancy<0)
          {
            $discrepancy = '<font color="#a23343">' . ($allocated-$work_hours) . '</font>'; 
          }
          
          echo $discrepancy;
          
       ?></td>
     </tr> 
    <?php       
          $count_items++;
          
     $export[] = array($project->getName(),$work_hours,$allocated,strip_tags($discrepancy));
           
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
   
<form action="<?php echo url_for('timeReport/export')?>" method="post" id="projects_export">
  <?php echo input_hidden_tag('filename','projects').input_hidden_tag('format').input_hidden_tag('export', json_encode($export)) ?>
</form>     
   
