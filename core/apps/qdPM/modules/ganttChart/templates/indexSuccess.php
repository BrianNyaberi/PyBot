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


<h3 class="page-title"><?php echo __('Gantt Chart') ?></h1>

<div><?php  include_component('ganttChart','filtersPreview') ?></div>

<?php  
  echo javascript_include_tag('/js/jsgantt/jsgantt.js');
  echo stylesheet_tag('/js/jsgantt/jsgantt.css');
        
  $pOpen = 0;
  if($sf_request->getParameter('projects_id')>0)
  {
    $pOpen = 1;
  }
  elseif(isset($filter_by['Projects']))
  {
    if(count(explode(',',$filter_by['Projects']))==1)
    {
      $pOpen = 1;
    }
  } 
    
?>

<div id="page_width"></div>

<div class="gantt" id="GanttChartDIV"></div>

<script type="text/javascript">

function resize_gantt_bar()
{   
   new_width = $('#page_width').width()-685;   
   $('#rightside').css('width',new_width+'px');
   $('#theTable').css('display','block'); 
}

var g = new JSGantt.GanttChart('g',document.getElementById('GanttChartDIV'), 'day');

g.setShowRes(1); // Show/Hide Responsible (0/1)
g.setShowDur(1); // Show/Hide Duration (0/1)
g.setShowComp(1); // Show/Hide % Complete(0/1)
g.setCaptionType('Complete');  // Set to Show Caption (None,Caption,Resource,Duration,Complete)
g.setShowStartDate(1); // Show/Hide Start Date(0/1)
g.setShowEndDate(1); // Show/Hide End Date(0/1)
g.setDateInputFormat('mm/dd/yyyy')  // Set format of input dates ('mm/dd/yyyy', 'dd/mm/yyyy', 'yyyy-mm-dd')
g.setDateDisplayFormat('mm/dd/yyyy') // Set format to display dates ('mm/dd/yyyy', 'dd/mm/yyyy', 'yyyy-mm-dd')
g.setFormatArr("day","week","month","quarter") // Set format options (up to 4 : "minute","hour","day","week","month","quarter")

<?php     
    $taskProjectId = 0;
    $parentItemId = 0;    
    $counter = 1;
    foreach($tasks_list as $tasks)
    {
    
      if($tasks['projects_id']!=$taskProjectId)
      {
        $taskProjectId = $tasks['projects_id'];
        echo "g.AddTaskItem(new JSGantt.TaskItem(" . $counter . ", '" . addslashes($tasks['Projects']['name']) . "','','','ffe763', '" . url_for('ganttChart/index?projects_id=' . $tasks['projects_id']). "','','',0,1,0," .  $pOpen . ",'','')); \n";
        $parentItemId = $counter;
        $counter++;
      }
    
      $estimated_time = $tasks['estimated_time'];
      
      if($estimated_time>0)
      {
        $estimated_title = $estimated_time . ' ' . t::__('hours');
      }
      else
      {
        $estimated_title= '';
      }
                                                               
      $start_date = app::ganttDateFormat($tasks['start_date']);
      $end_date = app::ganttDateFormat($tasks['due_date']);
      
      $level_padding = '';
      if(count($tasks_tree)>0)
      {
        if($tasks_tree[$tasks['id']]['level']>0)
        {
          $level_padding = str_repeat('&nbsp;-&nbsp;',$tasks_tree[$tasks['id']]['level']);
        } 
      }                              
            
      echo "g.AddTaskItem(new JSGantt.TaskItem(" . $counter . ", '" . $level_padding . addslashes($tasks['name']) . "','" . $start_date . "','" . $end_date . "','ffe763', '" . url_for('tasksComments/index?tasks_id=' . $tasks['id']. '&projects_id=' . $tasks['projects_id']). "','','" . ($tasks['tasks_status_id']>0 ? addslashes($tasks['TasksStatus']['name']) : '') . "'," . (int)$tasks['progress'] . ",0," . $parentItemId  . ",'','','" . url_for('tasks/info?id=' . $tasks['id'] . '&projects_id=' . $tasks['projects_id']) . "')); \n";
                
      $counter++; 
    } 
?>

g.Draw();	
g.DrawDependencies();

$(function() {
  resize_gantt_bar();
  $('#theTable').css('dispaly','block');   
});

$(document).bind('click', function() {
  $('#cluetip').hide().removeClass();
}); 
  
</script>
