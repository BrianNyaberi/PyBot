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
<?php

class ganttChartComponents extends sfComponents
{

  
  public function executeFilters(sfWebRequest $request)
  {  
    $m = array();
    
    $params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
            
    $m = app::getFilterMenuItemsByTable($m,'TasksPriority','Priority','ganttChart/index',$params);
    $m = app::getFilterMenuStatusItemsByTable($m,'TasksStatus','Status','ganttChart/index',$params);
    $m = app::getFilterMenuItemsByTable($m,'TasksLabels','Label','ganttChart/index',$params);
    $m = app::getFilterMenuItemsByTable($m,'TasksTypes','Type','ganttChart/index',$params);  
    $m = app::getFilterMenuUsers($m,'TasksAssignedTo', 'Assigned To','ganttChart/index',$params);
    $m = app::getFilterMenuUsers($m,'TasksCreatedBy','Created By','ganttChart/index',$params);    
    
    if(!$params)
    {
      $m = app::getFilterProjects($m,'ganttChart/index',$params,array(),$this->getUser());      
      $m = app::getFilterMenuStatusItemsByTable($m,'ProjectsStatus','Project Status','ganttChart/index',$params);
      $m = app::getFilterMenuItemsByTable($m,'ProjectsTypes','Project Type','ganttChart/index',$params);      
    }
    else
    {
      $m = app::getFilterMenuItemsByTable($m,'TasksGroups','Group','ganttChart/index',$params);  
      $m = app::getFilterMenuItemsByTable($m,'ProjectsPhases','Phase','ganttChart/index',$params);
      $m = app::getFilterMenuItemsByTable($m,'Versions','Version','ganttChart/index',$params);
    }
                            
    $this->m = array(array('title'=>__('Filters'),'submenu'=>$m));
  }
  
  public function executeFiltersPreview(sfWebRequest $request)
  {
    $this->filter_by = $this->getUser()->getAttribute('gantt_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : ''));
    $this->params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
    $this->filter_tables = array('TasksPriority'=>'Priority', 'TasksStatus'=>'Status','TasksTypes'=>'Type','TasksLabels'=>'Label','TasksAssignedTo'=>'Assigned To','TasksCreatedBy'=>'Created By','TasksGroups'=>'Group','ProjectsPhases'=>'Phase','Versions'=>'Version');
    
    $this->filter_tables['Projects']='Projects';    
    $this->filter_tables['ProjectsStatus']='Project Status';
    $this->filter_tables['ProjectsTypes']='Project Type';    
    
  }
      
}
