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

class projectsMenuController
{
  public $sf_user;
  
  public $sf_request;
  
  public function __construct($sf_user,$sf_request)
	{
	  $this->sf_user = $sf_user;
	  $this->sf_request = $sf_request;
	  $this->projects_id = $sf_request->getParameter('projects_id');
	  
    $this->access = array();        
	  $this->access['projects'] = Users::getAccessSchema('projects',$sf_user);
	  $this->access['tasks'] = Users::getAccessSchema('tasks',$sf_user,$this->projects_id);
	  $this->access['tickets'] = Users::getAccessSchema('tickets',$sf_user,$this->projects_id);
	  $this->access['discussions'] = Users::getAccessSchema('discussions',$sf_user,$this->projects_id);	  	  
	}
	
	public function buildMenu($sf_context)
  {
    $m = array();
    
    if($this->access['projects']['view'] or $this->access['projects']['view_own']) 
    {
      $m[] = array('title'=>__('Project Info'),'url'=>'projectsComments/index?projects_id=' . $this->projects_id,'is_selected'=>($sf_context->getModuleName()=='projectsComments'));
    }
    
    if($this->access['tasks']['view'] or $this->access['tasks']['view_own']) 
    {
      $s = array();
      $s[] = array('title'=>__('View All'),'url'=>'tasks/index?projects_id='. $this->projects_id);
      
      if($this->access['tasks']['insert'])
  	  {	                 
        $s[] = array('title'=>__('Add Task'),'url'=>'tasks/new?projects_id='. $this->projects_id,'modalbox'=>true);        
      }      
      
      $m[] = array('title'=>__('Tasks'),'submenu'=>$s,'is_selected'=>in_array($sf_context->getModuleName(),array('tasks','tasksComments')));
    }
    
    if($this->access['tickets']['view'] or $this->access['tickets']['view_own']) 
    {
      $s = array();
      $s[] = array('title'=>__('View All'),'url'=>'tickets/index?projects_id='. $this->projects_id);
      
      if($this->access['tickets']['insert'])
  	  {	                 
        $s[] = array('title'=>__('Add Ticket'),'url'=>'tickets/new?projects_id='. $this->projects_id,'modalbox'=>true);        
      }      
      
      $m[] = array('title'=>__('Tickets'),'submenu'=>$s,'is_selected'=>in_array($sf_context->getModuleName(),array('tickets','ticketsComments')));
    }
    
    if($this->access['discussions']['view'] or $this->access['discussions']['view_own']) 
    {
      $s = array();
      $s[] = array('title'=>__('View All'),'url'=>'discussions/index?projects_id='. $this->projects_id);
      if($this->access['discussions']['insert'])
  	  {	                 
        $s[] = array('title'=>__('Add Discussion'),'url'=>'discussions/new?projects_id='. $this->projects_id,'modalbox'=>true);        
      }      
      
      $m[] = array('title'=>__('Discussions'),'submenu'=>$s,'is_selected'=>in_array($sf_context->getModuleName(),array('discussions','discussionsComments')));
    }
    
    if(Users::hasAccess('view','projectsWiki',$this->sf_user,$this->projects_id))
    {
      $m[] = array('title'=>__('Wiki'),'url'=>'wiki/view?projects_id=' . $this->projects_id,'is_selected'=>($sf_context->getModuleName()=='wiki'));
    }
    
    if($this->access['tasks']['insert'] and $this->access['tasks']['edit']) 
    {
      if(sfConfig::get('app_use_tasks_groups')=='on')
      {
        $s = array();
        $s[] = array('title'=>__('View All'),'url'=>'tasksGroups/index?projects_id=' . $this->projects_id);
        $s[] = array('title'=>__('Add Group'),'url'=>'tasksGroups/new?projects_id=' . $this->projects_id,'modalbox'=>true);        
        $m[] = array('title'=>__('Tasks Groups'),'submenu'=>$s,'is_selected'=>($sf_context->getModuleName()=='tasksGroups'));
      }
      
      if(sfConfig::get('app_use_project_versions')=='on')
      {
        $s = array();
        $s[] = array('title'=>__('View All'),'url'=>'versions/index?projects_id=' . $this->projects_id);
        $s[] = array('title'=>__('Add Versions'),'url'=>'versions/new?projects_id=' . $this->projects_id,'modalbox'=>true);        
        $m[] = array('title'=>__('Versions'),'submenu'=>$s,'is_selected'=>($sf_context->getModuleName()=='versions'));
      }
      
      if(sfConfig::get('app_use_project_phases')=='on')
      {
        $s = array();
        $s[] = array('title'=>__('View All'),'url'=>'projectsPhases/index?projects_id=' . $this->projects_id);
        $s[] = array('title'=>__('Add Phase'),'url'=>'projectsPhases/new?projects_id=' . $this->projects_id,'modalbox'=>true);
        
        
        if(Doctrine_Core::getTable('ProjectsPhases')->createQuery()->addWhere('projects_id=?',$this->projects_id)->count()==0)
        {
          if(Doctrine_Core::getTable('Phases')->createQuery()->count()>0)
          {
            $ss = array();
            
            $phases_list = Doctrine_Core::getTable('Phases')->createQuery()->orderBy('name')->fetchArray();
            foreach($phases_list as $v)
            {                    
              $ss[] = array('title'=>$v['name'],'url'=>'projectsPhases/setDefaultPhases?projects_id=' . $this->projects_id . '&phase_id=' . $v['id']);            
            }
            
            $s[] = array('title'=>__('Default Phases'),'is_hr'=>true,'submenu'=>$ss);
          }
        }
        
        $m[] = array('title'=>__('Phases'),'submenu'=>$s,'is_selected'=>($sf_context->getModuleName()=='projectsPhases'));
      }
    }
    
    $s = array();    
    if($this->sf_user->hasCredential('reports_access_time'))
    {
      $s[] = array('title'=>__('Time Report'),'url'=>'timeReport/index?projects_id=' . $this->projects_id);
    }
    
    if($this->sf_user->hasCredential('reports_access_gantt'))
    {
      $s[] = array('title'=>__('Gantt Chart'),'url'=>'ganttChart/index?projects_id=' . $this->projects_id);
    }
    
    if(count($s)>0)
    {
      $m[] = array('title'=>__('Reports'),'submenu'=>$s);
    }
    
    return $m;
  }
		  
}
