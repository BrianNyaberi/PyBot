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

class menuController
{
  public $sf_user;
  
  public $sf_request;
  
  public function __construct($sf_user,$sf_request)
	{
	  $this->sf_user = $sf_user;
	  $this->user = $sf_user->getAttribute('user');
	  $this->sf_request = $sf_request;
	  
    $this->access = array();
    
    if($sf_user->isAuthenticated())
    {
	    $this->access['projects'] = Users::getAccessSchema('projects',$sf_user);
	    $this->access['tasks'] = Users::getAccessSchema('tasks',$sf_user);
      $this->access['tickets'] = Users::getAccessSchema('tickets',$sf_user);
      $this->access['discussions'] = Users::getAccessSchema('discussions',$sf_user);            
	  }
	}
	
	public function buildUserMenu()
	{
	  if(!$this->sf_user->isAuthenticated())
    {
      return array();
    }
       
    if($this->sf_user->getAttribute('users_group_id')==0)
    {
      return array(array('title'=>__('Logoff'),'url'=>'login/logoff'));
    }
    
    $user = $this->sf_user->getAttribute('user');
    
                  
    $s = array();
    $s[] = array('title'=>__('My Details'),'url'=>'myAccount/index','class'=>'fa-user');    
    
    if($this->sf_user->hasCredential('reports_access_time_personal'))
      $s[] = array('title'=>__('My Time Report'),'url'=>'timeReport/myTimeReport','class'=>'fa-clock-o');
                    
                                                
    if($this->sf_user->hasCredential('allow_manage_personal_scheduler'))
    { 
      $count = '';
      
      if(($count = Events::getCountTodaysEvents($this->sf_user->getAttribute('id')))>0)
      {
        $count = $count; 
      }
      else
      {
        $count = null;
      }
      
      
      $s[] = array('title'=>__('My Scheduler') ,'url'=>'scheduler/personal','class'=>'fa-calendar','counts'=>$count);             
    }
    
    if(sfConfig::get('app_use_skins')=='on')$s[] = array('title'=>__('Change Skin'),'url'=>'skins/index','modalbox'=>true,'class'=>'fa-picture-o');
    
    $s[] = array('title'=>__('Logoff'),'url'=>'login/logoff','is_hr'=>true,'class'=>'fa-sign-out');            
            
    return $s;
	}
	
	public function buildProjectsMenu()
	{
	  $s = array();
    
    $is_hr = false;
    
    $s[] = array('title'=>__('View All'),'url'=>'projects/index','is_hr'=>$is_hr);
	  
	  $reports = Doctrine_Core::getTable('ProjectsReports')
      ->createQuery()
      ->addWhere('(users_id="' . $this->sf_user->getAttribute('id') . '")')
      ->addWhere('display_in_menu=1')
      ->orderBy('sort_order, name')
      ->execute();
            
    foreach($reports as $r)
    {     
      $s[] = array('title'=>$r->getName(),'url'=>'projectsReports/view?id=' . $r->getId());
    }    
	  
	  
	  if($this->access['projects']['insert'])
	  {	                 
      $s[] = array('title'=>__('Add Project'),'url'=>'projects/new','modalbox'=>true,'is_hr'=>$is_hr);
      $is_hr = false;
    }

    
	  
    return array('title'=>__('Projects'),'url'=>'projects/index','submenu'=>$s,'class'=>'fa-sitemap');
	}
	
	public function buildTasksMenu()
	{
	  $s = array();  
    
    $is_hr = false; 
	  
    $s[] = array('title'=>__('View All'),'url'=>'tasks/index','is_hr'=>$is_hr);
    	  
	  if($this->access['tasks']['insert'])
	  {	                 
      $s[] = array('title'=>__('Add Task'),'url'=>'tasks/new','modalbox'=>true);
      $is_hr = false;
    }

    
	
    return array('title'=>__('Tasks'),'url'=>'tasks/index','submenu'=>$s,'class'=>'fa-tasks');
	}
	
	public function buildTicketsMenu()
	{
    $s = array();
	  $is_hr = false;
    
    $s[] = array('title'=>__('View All'),'url'=>'tickets/index','is_hr'=>$is_hr);
    	  
	  if($this->access['tickets']['insert'])
	  {	                 
      $s[] = array('title'=>__('Add Ticket'),'url'=>'tickets/new','modalbox'=>true);
      $is_hr = false;
    }

    
	
    return array('title'=>__('Tickets'),'url'=>'tickets/index','submenu'=>$s,'class'=>'fa-bell');
	}
	
	public function buildDiscussionsMenu()
	{
    $s = array();
	  $is_hr = false;
    $s[] = array('title'=>__('View All'),'url'=>'discussions/index','is_hr'=>$is_hr);
    	  	  
	  if($this->access['discussions']['insert'])
	  {	                 
      $s[] = array('title'=>__('Add Discussion'),'url'=>'discussions/new','modalbox'=>true);
      $is_hr = false;
    }
    
    return array('title'=>__('Discussions'),'url'=>'discussions/index','submenu'=>$s,'class'=>'fa-comments');
	}
	
	public function buildReportsMenuByTable($t)
	{
	  $url_action = $t;
	  $url_action[0] = strtolower($url_action[0]);
    
    $rm = array();
    if(count($reports = app::getUsersReportsChoicesByTable($t,$this->sf_user))>0)
    {
      foreach($reports as $id=>$name)
      {
        $rm[] = array('title'=>$name,'url'=>$url_action . '/view?id=' . $id);
      }
    }
    
    return $rm;
  }
	
	public function buildReportsMenu()
	{
	  $s = array();
	  
	  if($this->sf_user->hasCredential('reports_access_projects'))
	  {	    
	    //$rm = $this->buildReportsMenuByTable('ProjectsReports');
      $s[] = array('title'=>__('Project Reports'),'url'=>'projectsReports/index');
      //$s[] = array('title'=>__('Add New'),'url'=>'projectsReports/new','modalbox'=>true);
      //$s[] = array('title'=>__('View All'),'url'=>'projectsReports/index');
    }
    
    if($this->sf_user->hasCredential('reports_access_tasks'))
	  {	    
	    //$rm = $this->buildReportsMenuByTable('UserReports');
      $s[] = array('title'=>__('Tasks Reports'),'url'=>'userReports/index');
      //$s[] = array('title'=>__('Add New'),'url'=>'userReports/new','modalbox'=>true);
      //$s[] = array('title'=>__('View All'),'url'=>'userReports/index');
    }
    
    if($this->sf_user->hasCredential('reports_access_tickets'))
	  {	    
	    //$rm = $this->buildReportsMenuByTable('TicketsReports');
      $s[] = array('title'=>__('Tickets Reports'),'url'=>'ticketsReports/index');
      //$s[] = array('title'=>__('Add New'),'url'=>'ticketsReports/new','modalbox'=>true);
      //$s[] = array('title'=>__('View All'),'url'=>'ticketsReports/index');
    }
    
    if($this->sf_user->hasCredential('reports_access_discussions'))
	  {	    
	    //$rm = $this->buildReportsMenuByTable('DiscussionsReports');
      $s[] = array('title'=>__('Discussions Reports'),'url'=>'discussionsReports/index');
      //$s[] = array('title'=>__('Add New'),'url'=>'discussionsReports/new','modalbox'=>true);
      //$s[] = array('title'=>__('View All'),'url'=>'discussionsReports/index');
    }
    
    if($this->sf_user->hasCredential('reports_access_time'))
	  {
	    $s[] = array('title'=>__('Time Report'),'url'=>'timeReport/index','is_hr'=>true);	
	  }
	  
	  if($this->sf_user->hasCredential('reports_access_gantt'))
	  {
	    $s[] = array('title'=>__('Gantt Chart'),'url'=>'ganttChart/index','is_hr'=>true);	
	  }
            	    
	  if(count($s)>0)
    {
      return array('title'=>__('Reports'),'url'=>'projectsReports/index','submenu'=>$s,'class'=>'fa-bar-chart-o');
    }
    else
    {
      return false;
    }
  }
	
	public function buildUsersMenu()
	{		  
	  $s = array();	  	  
    $s[] = array('title'=>__('View All'),'url'=>'users/index');
    $s[] = array('title'=>__('Add User'),'url'=>'users/new','modalbox'=>true);    
        
    if($this->sf_user->getAttribute('users_group_id')!=0)
    {
      $s[] = array('title'=>__('Send Email'),'url'=>'users/sendEmail','is_hr'=>true);
    }
    
    return array('title'=>__('Users'),'url'=>'users/index','submenu'=>$s,'class'=>'fa-user');    
	}
	
	public function buildContactsMenu()
	{
    $s = array();	  	  
    $s[] = array('title'=>__('Add Contact'),'url'=>'contacts/new','modalbox'=>true);    
    $s[] = array('title'=>__('View All'),'url'=>'contacts/index');
    
    return array('title'=>__('Contacts'),'url'=>'contacts/index','submenu'=>$s);
	}
	
	public function buildConfigurationMenu()
	{
    return array('title'=>__('Configuration'),
                 'class'=>'fa-gear', 
                 'url'=>'configuration/index',
                 'submenu'=>array(
                    array('title'=>__('General'),
                      'url'=>'configuration/index',
                      'submenu'=>array(
                        array('title'=>__('General'),'url'=>'configuration/index?type=general'),
                        array('title'=>__('Features'),'url'=>'configuration/index?type=features'),
                        array('title'=>__('Email Options'),'url'=>'configuration/index?type=email_options'),
                        array('title'=>__('LDAP'),'url'=>'configuration/index?type=ldap'),
                        array('title'=>__('Login Page'),'url'=>'configuration/index?type=login'),
                        array('title'=>__('New User Creation'),'url'=>'configuration/index?type=user'),                        
                      )),
                    array('title'=>__('Users'),
                      'url'=>'usersGroups/index',
                      'submenu'=>array(
                        array('title'=>__('Users Groups'),'url'=>'usersGroups/index'),                        
                        array('title'=>__('Extra Fields'),'url'=>'extraFields/index?bind_type=users'),                        
                      )),
                    array('title'=>__('Projects'),
                      'url'=>'projectsStatus/index',
                      'submenu'=>array(
                        array('title'=>__('Status'),'url'=>'projectsStatus/index'),
                        array('title'=>__('Types'),'url'=>'projectsTypes/index'),                        
                        array('title'=>__('Default Phases'),'url'=>'phases/index'),
                        array('title'=>__('Phases Status'),'url'=>'phasesStatus/index'),
                        array('title'=>__('Versions Status'),'url'=>'versionsStatus/index'),                        
                        array('title'=>__('Extra Fields'),'url'=>'extraFields/index?bind_type=projects'),                        
                      )),
                    array('title'=>__('Tasks'),
                      'url'=>'tasksStatus/index',
                      'submenu'=>array(
                        array('title'=>__('Status'),'url'=>'tasksStatus/index'),
                        array('title'=>__('Types'),'url'=>'tasksTypes/index'),
                        array('title'=>__('Labels'),'url'=>'tasksLabels/index'),
                        array('title'=>__('Priority'),'url'=>'tasksPriority/index'),
                        array('title'=>__('Tasks Listing'),'url'=>'configuration/index?type=tasks_columns_list'),
                        array('title'=>__('Extra Fields'),'url'=>'extraFields/index?bind_type=tasks'),                        
                      )),
                    array('title'=>__('Tickets'),
                      'url'=>'departments/index',
                      'submenu'=>array(
                        array('title'=>__('Departments'),'url'=>'departments/index'),
                        array('title'=>__('Status'),'url'=>'ticketsStatus/index'),
                        array('title'=>__('Types'),'url'=>'ticketsTypes/index'),                                                
                        array('title'=>__('Extra Fields'),'url'=>'extraFields/index?bind_type=tickets'),                        
                      )),
                    array('title'=>__('Discussions'),
                      'url'=>'discussionsStatus/index',
                      'submenu'=>array(
                        array('title'=>__('Status'),'url'=>'discussionsStatus/index'),                        
                        array('title'=>__('Extra Fields'),'url'=>'extraFields/index?bind_type=discussions'),                        
                      )),                    

                 ));
  }
  
  
	public function buildToolsMenu()
	{
     return array('title'=>__('Tools'),
                  'class'=>'fa-wrench',
                  'url'=>'tools/backup',
                 'submenu'=>array(                    
                    array('title'=>__('Backups'),'url'=>'tools/backup'),                       
                    array('title'=>__('Import Tasks from XLS file'),'url'=>'tools/xlsTasksImport'),                                                                                     
                   ));
  }
  
  public function buildUpgradeToExtendedMenu()
	{
     return array('title'=>'qdPM Extended','url'=>'http://qdpm-ex.com/','target'=>'_blnak',
                  'class'=>'fa-level-up',
                 'submenu'=>array(                    
                    array('title'=>'See qdPM Extended Features','url'=>'http://qdpm-ex.com/features.php','target'=>'_blnak'),                       
                    array('title'=>'Compare Extended and Free version','url'=>'http://qdpm-ex.com/compare-extended-and-free-version-of-qdpm-pid-17.html','target'=>'_blnak'),
                    array('title'=>'Ask a question about qdPM Extended','url'=>'http://qdpm-ex.com/contact_us.php','target'=>'_blnak'),                                                                                                         
                   ));
  }
  
  public function buildMenu()
  {
    if(!$this->sf_user->isAuthenticated())
    {
      return $this->buildPublicMenu();
    }
    
    $m = array();
    
    if($this->sf_user->getAttribute('users_group_id')==0)
    {
      $m[] = $this->buildUsersMenu();
      $m[] = $this->buildConfigurationMenu();
      
      return $m;
    }
    
    $m[] = array('title'=>__('Dashboard'),'url'=>'dashboard/index','class'=>'fa-home');
            
    if($this->access['projects']['view'] or $this->access['projects']['view_own']) 
    {
      $m[] = $this->buildProjectsMenu();
    }
    
    if($this->access['tasks']['view'] or $this->access['tasks']['view_own']) 
    {
      $m[] = $this->buildTasksMenu();
    }
    
    if($this->access['tickets']['view'] or $this->access['tickets']['view_own']) 
    {
      $m[] = $this->buildTicketsMenu();
    }
    
    if($this->access['discussions']['view'] or $this->access['discussions']['view_own']) 
    {
      $m[] = $this->buildDiscussionsMenu();
    }
    
    if($rm = $this->buildReportsMenu())
    {  
      $m[] = $rm;      
    }
      
    if($this->sf_user->hasCredential('allow_manage_users')) $m[] = $this->buildUsersMenu();    

    if($this->sf_user->hasCredential('allow_manage_configuration'))
    {
      $m[] = $this->buildConfigurationMenu();
      $m[] = $this->buildToolsMenu();
      $m[] = $this->buildUpgradeToExtendedMenu();
    }
                 
    return $m;
  }
  
  public function buildPublicMenu()
  {
    $m = array();
    $m[] = array('title'=>__('Login'),'url'=>'login/index');
        
    return $m;
  }
  
}
