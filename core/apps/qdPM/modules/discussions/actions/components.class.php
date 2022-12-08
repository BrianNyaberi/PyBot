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

class discussionsComponents extends sfComponents
{
  public function executeListing(sfWebRequest $request)
  {
    if(!isset($this->reports_id)) $this->reports_id = false;
    
    
    $q = Doctrine_Core::getTable('Discussions')->createQuery('d')          
          ->leftJoin('d.DiscussionsStatus ds')                                                 
          ->leftJoin('d.Projects p')
          ->leftJoin('d.Users');
          
    if($request->hasParameter('projects_id'))
    {
      $q->addWhere('projects_id=?',$request->getParameter('projects_id'));
      
      if(Users::hasAccess('view_own','discussions',$this->getUser(),$request->getParameter('projects_id')))
      {                 
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',d.assigned_to) or d.users_id='" . $this->getUser()->getAttribute('id') . "'");
      }
    }
    else
    {
      if(Users::hasAccess('view_own','projects',$this->getUser()))
      {       
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
      
      if(Users::hasAccess('view_own','discussions',$this->getUser()))
      {                 
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',d.assigned_to) or d.users_id='" . $this->getUser()->getAttribute('id') . "'");
      }
    }      
                              
    if($this->reports_id>0)
    {
      $q = DiscussionsReports::addFiltersToQuery($q,$this->reports_id,$this->getUser());      
    }                
    elseif($request->hasParameter('search'))
    {    
      $q = app::addSearchQuery($q, $request->getParameter('search'),'DiscussionsComments','d',$request->getParameter('search_by_extrafields'));
      $q = app::addListingOrder($q,'discussions',$this->getUser());
    }
    else
    {
      $q = Discussions::addFiltersToQuery($q,$this->getUser()->getAttribute('discussions_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : '')));
      $q = app::addListingOrder($q,'discussions',$this->getUser(), (int)$request->getParameter('projects_id'));            
    }
    
    if(sfConfig::get('app_rows_limit')>0)
    {
      $this->pager = new sfDoctrinePager('Discussions', sfConfig::get('app_rows_limit'));
      $this->pager->setQuery($q);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
    }
                                  
    $this->discussions_list = $q->fetchArray();
            
    if(isset($this->is_dashboard))
    {
      $this->url_params = 'redirect_to=dashboard';
      $this->display_insert_button = true;
    }
    elseif($this->reports_id>0)
    {
      $this->url_params = 'redirect_to=discussionsReports' . $this->reports_id;
      $this->display_insert_button = true;
    }
    else
    {
      $this->url_params = '';
      if($request->hasParameter('projects_id')) $this->url_params = 'projects_id=' . $request->getParameter('projects_id');
      $this->display_insert_button = true;
    }
    
    $this->tlId = rand(1111111,9999999);
  }
  
  public function executeFilters(sfWebRequest $request)
  {  
    $m = array();
    
    $params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
                
    $m = app::getFilterMenuItemsByTable($m,'DiscussionsStatus','Status','discussions/index',$params);      
    
    
    if(!Users::hasAccess('view_own','projects',$this->getUser()))
    {
      $m = app::getFilterMenuUsers($m,'DiscussionsAssignedTo', 'Assigned To','discussions/index',$params);
      $m = app::getFilterMenuUsers($m,'DiscussionsCreatedBy','Created By','discussions/index',$params);
    }
      
    if(!$params)
    {
      $m = app::getFilterProjects($m,'discussions/index',$params,array(),$this->getUser());
      $m = app::getFilterMenuItemsByTable($m,'ProjectsStatus','Projects Status','discussions/index',$params);
      $m = app::getFilterMenuItemsByTable($m,'ProjectsTypes','Projects Types','discussions/index',$params);      
    }
                            
    $this->m = array(array('title'=>__('Filters'),'submenu'=>$m));
  }
  
  public function executeFiltersPreview(sfWebRequest $request)
  {
    $this->filter_by = $this->getUser()->getAttribute('discussions_filter' . ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : ''));
    $this->params = ((int)$request->getParameter('projects_id')>0 ? 'projects_id=' . $request->getParameter('projects_id') : false);
    $this->filter_tables = array('DiscussionsStatus'=>'Status','DiscussionsAssignedTo'=>'Assigned To','DiscussionsCreatedBy'=>'Created By');
    
    $this->filter_tables['Projects']='Projects';    
    $this->filter_tables['ProjectsStatus']='Project Status';
    $this->filter_tables['ProjectsTypes']='Project Type';    
  
  }
      
  public function executeDetails()
  {
       
  }
  
  public function executeEmailBody()
  {
       
  }
  
  public function executeRelatedDiscussionsToTasks()
  {          
    $task = Doctrine_Core::getTable('Tasks')->find($this->tasks_id); 
    
    $this->discussions_list = array();
    
    if($task)
    {                
      $this->discussions_list = $q = Doctrine_Core::getTable('Discussions')->createQuery('t')          
            ->leftJoin('t.DiscussionsStatus ts')                      
            ->leftJoin('t.Projects p')    
            ->addWhere('id=?',$task->getDiscussionId())                            
            ->fetchArray();
    }
  }
  
}
