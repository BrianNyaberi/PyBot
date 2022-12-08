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

/**
 * ganttChart actions.
 *
 * @package    sf_sandbox
 * @subpackage ganttChart
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ganttChartActions extends sfActions
{
  protected function checkProjectsAccess($projects)
  {    
    Projects::checkViewOwnAccess($this,$this->getUser(),$projects);    
  }
  
  protected function checkTasksAccess($access,$tasks=false,$projects=false)
  {
    if($projects)
    {
      Users::checkAccess($this,$access,'tasks',$this->getUser(),$projects->getId());
      if($tasks)
      {
        Tasks::checkViewOwnAccess($this,$this->getUser(),$tasks,$projects);
      }
    }
    else
    {
      Users::checkAccess($this,$access,'tasks',$this->getUser());
    }
  }
  
  protected function add_pid($request,$sep='?')
  {
    if((int)$request->getParameter('projects_id')>0)
    {
      return $sep . 'projects_id=' . $request->getParameter('projects_id');
    }
    else
    {
      return '';
    }
  }
  
  protected function get_pid($request)
  {
    return ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : '');
  }
  
  public function executeIndex(sfWebRequest $request)
  {  
    app::setPageTitle('Gantt Chart',$this->getResponse());
    
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkTasksAccess('view',false,$this->projects);
    }
    else
    {
      $this->checkTasksAccess('view');
    }
                            
    if(!$this->getUser()->hasAttribute('gantt_filter' . $this->get_pid($request)))
    {
      $this->getUser()->setAttribute('gantt_filter' . $this->get_pid($request), Tasks::getDefaultFilter($request,$this->getUser(),'ganttChart'));
    }
                     
    $this->filter_by = $this->getUser()->getAttribute('gantt_filter' . $this->get_pid($request));
        
    if($fb = $request->getParameter('filter_by'))
    {
      $this->filter_by[key($fb)]=current($fb);
      $this->getUser()->setAttribute('gantt_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('ganttChart/index' . $this->add_pid($request));
    }
    
    if($request->hasParameter('remove_filter'))
    {
      unset($this->filter_by[$request->getParameter('remove_filter')]);    
      $this->getUser()->setAttribute('gantt_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('ganttChart/index' . $this->add_pid($request));
    }
     
              
    $this->tasks_tree = array();
    
    $this->tasks_list = $this->getTasks($request,$this->tasks_tree);      
  }
  
  public function executeSaveFilter(sfWebRequest $request)
  {
    $this->setTemplate('saveFilter','app');
  }
  
  public function executeDoSaveFilter(sfWebRequest $request)
  {
    Tasks::saveTasksFilter($request,$this->getUser()->getAttribute('gantt_filter' . $this->get_pid($request)),$this->getUser(),'ganttChart');
    
    $this->getUser()->setFlash('userNotices', t::__('Filter Saved'));
    $this->redirect('ganttChart/index' . $this->add_pid($request));
  }
  
  protected function getTasks($request,$tasks_tree)
  {
    $q = Doctrine_Core::getTable('Tasks')->createQuery('t')
          ->leftJoin('t.TasksPriority tp')
          ->leftJoin('t.TasksStatus ts')
          ->leftJoin('t.TasksLabels tl')
          ->leftJoin('t.TasksTypes tt')
          ->leftJoin('t.TasksGroups tg')
          ->leftJoin('t.ProjectsPhases pp')
          ->leftJoin('t.Versions v')
          ->leftJoin('t.Projects p')
          ->leftJoin('t.Users');
          
    if($request->hasParameter('projects_id'))
    {
      $q->addWhere('projects_id=?',$request->getParameter('projects_id'));
      
      if(Users::hasAccess('view_own','tasks',$this->getUser(),$request->getParameter('projects_id')))
      {                 
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',t.assigned_to) or t.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
    }
    else
    {
      if(Users::hasAccess('view_own','projects',$this->getUser()))
      {       
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
      
      if(Users::hasAccess('view_own','tasks',$this->getUser()))
      {                 
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',t.assigned_to) or t.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
    }
    
    $q = Tasks::addFiltersToQuery($q,$this->getUser()->getAttribute('gantt_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : '')));
    
    $q->addWhere('t.due_date is not null and t.start_date is not null');
          
    $q->orderBy('p.name, t.start_date');
    
    return $q->fetchArray();  
      
  }
}
