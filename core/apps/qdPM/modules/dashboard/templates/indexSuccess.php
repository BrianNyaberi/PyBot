<?php
/**
* WORK SMART
*/
?>
<?php 
  echo '<div id="dashboard-csg-box">' . button_tag_modalbox(__('Configure Dashboard'),'dashboard/configure') . '</div>';
  
  if(count($reports)==0) echo '<br><div>' . __('No reports found to display on dashboard') . '</div>';
  
  foreach($reports as $report)
  {    
  
    switch(true)
    {       
      case strstr($report,'projectsReports'):
          if($r = Doctrine_Core::getTable('ProjectsReports')->find(str_replace('projectsReports','',$report)))
          {  
           echo '
            <div class="portlet">
  						<div class="portlet-title">
  							<div class="caption">
  								 <a href="' . url_for('projectsReports/view?id=' . $r->getId()) . '">' . $r->getName() . '</a>
  							</div>
                <div class="tools">
  								<a href="javascript: ;" dashboard-report="' . $report . '" class="dashboard-report ' . (in_array($report,$hidden_dashboard_reports) ? 'expand':'collapse'). '"></a>  								
  							</div>
  						</div>
              <div class="portlet-body" id="' . $report . '" style="display:' . (in_array($report,$hidden_dashboard_reports) ? 'none':'block'). '" >
               <div>' . get_component('projects','listing',array('reports_id'=>$r->getId(), 'is_dashboard'=>true)) . '</div>
              </div>              
            </div>  
           ';          
          } 
        break;
      case strstr($report,'userReports'):
          if($r = Doctrine_Core::getTable('UserReports')->find(str_replace('userReports','',$report)))
          {  
          
          echo '
            <div class="portlet">
  						<div class="portlet-title">
  							<div class="caption">
  								 <a href="' . url_for('userReports/view?id=' . $r->getId()) . '">' . $r->getName() . '</a>
  							</div>
                <div class="tools">
  								<a href="javascript: ;" dashboard-report="' . $report . '" class="dashboard-report ' . (in_array($report,$hidden_dashboard_reports) ? 'expand':'collapse'). '"></a>  								
  							</div>
  						</div>
              <div class="portlet-body" id="' . $report . '" style="display:' . (in_array($report,$hidden_dashboard_reports) ? 'none':'block'). '" >
               <div>' . get_component('tasks','listing',array('reports_id'=>$r->getId(), 'is_dashboard'=>true)) . '</div>
              </div>              
            </div>  
           '; 

          } 
        break;
      case strstr($report,'ticketsReports'):
          if($r = Doctrine_Core::getTable('TicketsReports')->find(str_replace('ticketsReports','',$report)))
          {
            echo '
            <div class="portlet">
  						<div class="portlet-title">
  							<div class="caption">
  								 <a href="' . url_for('ticketsReports/view?id=' . $r->getId()) . '">' . $r->getName() . '</a>
  							</div>
                <div class="tools">
  								<a href="javascript: ;" dashboard-report="' . $report . '" class="dashboard-report ' . (in_array($report,$hidden_dashboard_reports) ? 'expand':'collapse'). '"></a>  								
  							</div>
  						</div>
              <div class="portlet-body" id="' . $report . '" style="display:' . (in_array($report,$hidden_dashboard_reports) ? 'none':'block'). '" >
               <div>' . get_component('tickets','listing',array('reports_id'=>$r->getId(), 'is_dashboard'=>true)) . '</div>
              </div>              
            </div>  
           '; 
          } 
        break;
      case strstr($report,'discussionsReports'):
          if($r = Doctrine_Core::getTable('DiscussionsReports')->find(str_replace('discussionsReports','',$report)))
          {  
            echo '
            <div class="portlet">
  						<div class="portlet-title">
  							<div class="caption">
  								 <a href="' . url_for('discussionsReports/view?id=' . $r->getId()) . '">' . $r->getName() . '</a>
  							</div>
                <div class="tools">
  								<a href="javascript: ;" dashboard-report="' . $report . '" class="dashboard-report ' . (in_array($report,$hidden_dashboard_reports) ? 'expand':'collapse'). '"></a>  								
  							</div>
  						</div>
              <div class="portlet-body" id="' . $report . '" style="display:' . (in_array($report,$hidden_dashboard_reports) ? 'none':'block'). '" >
               <div>' . get_component('discussions','listing',array('reports_id'=>$r->getId(), 'is_dashboard'=>true)) . '</div>
              </div>              
            </div>  
           '; 
        
          } 
        break;
    }
  }
?>

<script>
  $('.dashboard-report').on('click',function(){    
    expand_dashboard_report($(this).attr('dashboard-report'),'<?php echo url_for("dashboard/expandReport") ?>')
  })
</script>
