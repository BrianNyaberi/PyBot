<?php
/**
* WORK SMART
*/
?>
<?php

class projectsComponents extends sfComponents
{
  public function executeListing(sfWebRequest $request)
  {
    if(!isset($this->reports_id)) $this->reports_id = false;
            
    $q = Doctrine_Core::getTable('Projects')->createQuery('p')          
          ->leftJoin('p.ProjectsStatus ps')
          ->leftJoin('p.ProjectsTypes pt')          
          ->leftJoin('p.Users');
          
    if(Users::hasAccess('view_own','projects',$this->getUser()))
    {       
      $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',p.team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
    }
    
    if($this->reports_id>0)
    {
      $q = ProjectsReports::addFiltersToQuery($q,$this->reports_id, $this->getUser());      
    }                
    elseif($request->hasParameter('search'))
    {    
      $q = app::addSearchQuery($q, $request->getParameter('search'),'ProjectsComments','p',$request->getParameter('search_by_extrafields'));
      $q = app::addListingOrder($q,'projects',$this->getUser());
    }
    else
    {
      $q = Projects::addFiltersToQuery($q,$this->getUser()->getAttribute('projects_filter'));
      $q = app::addListingOrder($q,'projects',$this->getUser());
    }
    
    if(sfConfig::get('app_rows_limit')>0)
    {
      $this->pager = new sfDoctrinePager('Projects', sfConfig::get('app_rows_limit'));
      $this->pager->setQuery($q);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
    }
                          
    $this->projects_list = $q->fetchArray();
        
                 
    if(isset($this->is_dashboard))
    {
      $this->url_params = 'redirect_to=dashboard';
      $this->display_insert_button = true;
    }
    elseif($this->reports_id>0)
    {
      $this->url_params = 'redirect_to=projectsReports' . $this->reports_id;
      $this->display_insert_button = true;
    }
    else
    {
      $this->url_params = '';                         
      $this->display_insert_button = true;
    }
    
    $this->tlId = rand(1111111,9999999);
  }
  
  public function executeFilters()
  {
    $m = array();
                
    $m = app::getFilterMenuItemsByTable($m,'ProjectsStatus','Status','projects/index');
    $m = app::getFilterMenuItemsByTable($m,'ProjectsTypes','Type','projects/index');
    
    if(!Users::hasAccess('view_own','projects',$this->getUser()))
    {    
      $m = app::getFilterMenuUsers($m,'Users','In Team','projects/index');      
    }
                                        
    $this->m = array(array('title'=>'<i class="fa fa-tasks"></i> ' . __('Filters'),'submenu'=>$m));
  }
  
  public function executeFiltersPreview()
  {
    $this->filter_by = $this->getUser()->getAttribute('projects_filter');
    $this->filter_tables = array( 'ProjectsStatus'=>'Status','ProjectsTypes'=>'Type','Users'=>'In Team');
    
  }
  
  public function executeTeam()
  {
    if($this->project->isNew())
    {
      $this->in_team = array();
      $this->in_role = array();
    }
    else
    {
      $this->in_team = explode(',',$this->project->getTeam());
      $this->project_id = $this->project->getId();  
    }
            
    $this->users_list = Users::getChoices();            
  }
  
  public function executeShortInfo()
  {
  
  }
  
  public function executeDetails()
  {
       
  }
  
  public function executeEmailBody()
  {
       
  }
}
