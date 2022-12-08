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
 * tasks actions.
 *
 * @package    sf_sandbox
 * @subpackage tasks
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tasksActions extends sfActions
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
  
  protected function set_fitlers($request)
  {
    if(!$this->getUser()->hasAttribute('tasks_filter' . $this->get_pid($request)))
    {
      $this->getUser()->setAttribute('tasks_filter' . $this->get_pid($request), Tasks::getDefaultFilter($request,$this->getUser()));
    }
  }
  
  public function executeIndex(sfWebRequest $request)
  {
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
                            
    $this->set_fitlers($request);
                     
    $this->filter_by = $this->getUser()->getAttribute('tasks_filter' . $this->get_pid($request));
        
    if($fb = $request->getParameter('filter_by'))
    {
      $this->filter_by[key($fb)]=current($fb);
      $this->getUser()->setAttribute('tasks_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('tasks/index' . $this->add_pid($request));
    }
    
    if($request->hasParameter('remove_filter'))
    {
      unset($this->filter_by[$request->getParameter('remove_filter')]);    
      $this->getUser()->setAttribute('tasks_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('tasks/index' . $this->add_pid($request));
    }
        
    if($set_order = $request->getParameter('set_order'))
    {            
      $this->getUser()->setAttribute('tasks_listing_order' . $this->get_pid($request),$set_order);
      
      $this->redirect('tasks/index' . $this->add_pid($request));
    }
      
    app::setPageTitle('Tasks',$this->getResponse()); 
        
    
  }
  
  
  public function executeInfo(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('id')));
    
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);        
  }  

  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($projects);
      $this->checkTasksAccess('insert',false,$projects);
      
      $this->form = new TasksForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));
    }
    else
    {
      $this->checkTasksAccess('insert');
    }
    
    $this->set_fitlers($request);

    $this->filter_by = $this->getUser()->getAttribute('tasks_filter' . $this->get_pid($request));
    
    $this->choices = app::getProjectChoicesByUser($this->getUser(),true,'tasks',true);
    
    if(count($this->choices)==2)
    {
      unset($this->choices['']);
    }
  }
  
  public function executeNewTask(sfWebRequest $request)
  {
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkTasksAccess('insert',false,$projects);
    
    $this->form = new TasksForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));

    $this->checkProjectsAccess($projects);
    $this->checkTasksAccess('insert',false,$projects);
    
    $this->form = new TasksForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkTasksAccess('edit',$tasks, $projects);
    
    $this->set_fitlers($request);

    $this->filter_by = $this->getUser()->getAttribute('tasks_filter' . $this->get_pid($request));
    
    $this->form = new TasksForm($tasks,array('projects'=>$projects,'sf_user'=>$this->getUser()));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkTasksAccess('edit',$tasks, $projects);
    
    $this->form = new TasksForm($tasks,array('projects'=>$projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {    
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkTasksAccess('delete',$tasks,$projects);
    
    $tasks->delete();
    Attachments::resetAttachments();

    $this->redirect('tasks/index' . $this->add_pid($request));
  }


  protected function getSendTo($form)
  {
    $send_to = array();
     
    $allow_send = '';   
    
    if($form->getObject()->isNew())
    {
      $send_to['new'] = $form['assigned_to']->getValue();;
      
      $allow_send = 'new';
    }
    else
    {  
      if(is_array($form['assigned_to']->getValue()))  
      if(count($new_users = array_diff($form['assigned_to']->getValue(),explode(',',$form->getObject()->getAssignedTo())))>0)
      {
        $send_to['new_assigned'] = $new_users;
      }
    
      if($form->getObject()->getTasksStatusId()!=$form['tasks_status_id']->getValue())
      {
        if(isset($send_to['new_assigned']))
        {
          $send_to['status'] = array_diff(explode(',',$form->getObject()->getAssignedTo()),$new_users);
        }
        else
        {
          $send_to['status'] = $form['assigned_to']->getValue();
        }
        
        $allow_send = 'status';  
      }
    }
    
    if(strlen($allow_send)>0 and sfConfig::get('app_notify_all_tasks')=='on')
    {
      $send_to[$allow_send] = Projects::getTeamUsersByAccess($form['projects_id']->getValue(),'tasks');                  
    }
    
    
    return $send_to;    
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if(is_array($form['assigned_to']->getValue()))
        $form->setFieldValue('assigned_to',implode(',',$form['assigned_to']->getValue()));
    
      $send_to = $this->getSendTo($form);
      
      if($form->getObject()->isNew()){ $previeous_status = false; }else{ $previeous_status = $form->getObject()->getTasksStatusId(); }
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
    
      $form->protectFieldsValue();
      
      $tasks = $form->save();
      
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$tasks->getId(),'tasks',$this->getUser(),$request);
      
      Attachments::insertAttachments($request->getFiles(),'tasks',$tasks->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      $tasks = $this->setClosedDate($previeous_status,$tasks);
      
      $tasks = $this->checkIfAssignedToChanged($send_to,$tasks);
      
      $this->addRelatedItems($tasks,$request);
      
      Tasks::sendNotification($this,$tasks,$send_to,$this->getUser());
                                    
      $this->redirect_to($request->getParameter('redirect_to'),$tasks->getProjectsId(), $tasks->getId(),$request,$tasks);
    }
  }
  
  
  protected function addRelatedItems($tasks,$request)
  {
    if($request->getParameter('related_tickets_id')>0)
    {
      $tasks->setTicketsId($request->getParameter('related_tickets_id'));
      $tasks->save();
    }
    elseif($request->getParameter('related_discussions_id')>0)
    {
      $tasks->setDiscussionId($request->getParameter('related_discussions_id'));
      $tasks->save();  
    }  
  }
  
  protected function checkIfAssignedToChanged($send_to,$tasks)
  {
    if(isset($send_to['new_assigned']))
    {                  
      $c = new TasksComments;
      $c->setDescription(t::__('Assigned To') . ': ' .  Users::getNameById(implode(',',$send_to['new_assigned']),', '));
      $c->setTasksId($tasks->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setCreatedBy($this->getUser()->getAttribute('id'));
      $c->save();
            
      $tasks->save();
    }
    
    return $tasks;    
  
  }
  
  protected function setClosedDate($previeous_status,$tasks)
  {
    if(in_array($tasks->getTasksStatusId(),app::getStatusByGroup('closed','TasksStatus')) and $previeous_status!=$tasks->getTasksStatusId())
    {
      $tasks->setClosedDate(date('Y-m-d H:i:s'));
      $tasks->save();      
    }
    
    if(!in_array($tasks->getTasksStatusId(),app::getStatusByGroup('closed','TasksStatus')) and $previeous_status!=$tasks->getTasksStatusId())
    {
      $tasks->setClosedDate(null);
      $tasks->save();      
    }                             
    
    if($previeous_status!=$tasks->getTasksStatusId() and $previeous_status>0)
    {
      $c = new TasksComments;
      $c->setTasksStatusId($tasks->getTasksStatusId());
      $c->setTasksId($tasks->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setCreatedBy($this->getUser()->getAttribute('id'));
      $c->save();
            
      $tasks->save();
    }
     
    return $tasks;    
  }
  
  protected function redirect_to($redirect_to,$projects_id,$tasks_id,$request=false,$tasks = false)
  {
    if($request)
    {
      if($request->getParameter('related_tickets_id')>0)
      {
        $this->redirect('ticketsComments/index?projects_id=' . $projects_id . '&tickets_id=' . $request->getParameter('related_tickets_id'));
      }
      elseif($request->getParameter('related_discussions_id')>0)
      {
        $this->redirect('discussionsComments/index?projects_id=' . $projects_id . '&discussions_id=' . $request->getParameter('related_discussions_id'));
      }
    }
  
    switch(true)
    {
      case $redirect_to=='dashboard': $this->redirect('dashboard/index');
        break;
      case $redirect_to=='tasksList': $this->redirect('tasks/index');
        break;  
      case $redirect_to=='tasksComments': $this->redirect('tasksComments/index?projects_id=' . $projects_id . '&tasks_id=' . $tasks_id);
        break;
      case $redirect_to=='parentTask': $this->redirect('tasksComments/index?projects_id=' . $projects_id . '&tasks_id=' . $tasks->getParentId());
        break;
      case strstr($redirect_to,'userReports'): $this->redirect('userReports/view?id=' . str_replace('userReports','',$redirect_to));
        break;
      default: $this->redirect('tasks/index' . ($projects_id>0 ?'?projects_id=' . $projects_id:''));
        break;  
    }
  }
    
    
  public function executeExport(sfWebRequest $request)
  {
    /*check access*/
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
  
    $this->columns = array(
                           'Projects'       => t::__('Project Name'),
                           'id'             => t::__('Id'),
                           'TasksGroups'    => t::__('Group'),
                           'Versions'        => t::__('Version'),
                           'ProjectsPhases' => t::__('Phase'),                 
                           'TasksPriority'  => t::__('Priority'),
                           'TasksLabels'     => t::__('Label'),                                  
                           'name'     => t::__('Name'),
                           'description'     => t::__('Description'),
                           'TasksStatus'    => t::__('Status'),
                           'TasksTypes'      => t::__('Type'),                 
                           'assigned_to'    => t::__('Assigned To'),
                           'Users'     => t::__('Created By'),
                           'estimated_time' => t::__('Est. Time'),
                           'work_hours'     => t::__('Work Hours'),                                                      
                           'start_date'     => t::__('Start Date'),
                           'due_date'       => t::__('Due Date'),
                           'progress'       => t::__('Progress'),
                           'created_at'     => t::__('Created At'),
                          );
                           
    $extra_fields = ExtraFieldsList::getFieldsByType('tasks',$this->getUser(),false,array('all'=>true));
    
    foreach($extra_fields as $v)
    {
      $this->columns['extra_field_' . $v['id']]=$v['name'];
    }   
                                        
    $this->columns['url']=t::__('Url');
    
    if($fields = $request->getParameter('fields'))
    {
      $separator = "\t";
      $format = $request->getParameter('format','.csv');
      $filename = $request->getParameter('filename','tasks');
			
			header("Content-type: Application/octet-stream");      
			header("Content-disposition: attachment; filename=" . $filename . "." . $format);
			header("Pragma: no-cache");
			header("Expires: 0");
    
      $content = '';
      foreach($fields as $f)
      {
        $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$this->columns[$f]) . $separator;
      }
      $content .= "\n";
      
      if($format=='csv')
      {
        echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
      }
      else
      {
        echo $content;
      }
    
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
       $q = Doctrine_Core::getTable('Tasks')->createQuery('t')
          ->leftJoin('t.TasksPriority tp')
          ->leftJoin('t.TasksStatus ts')
          ->leftJoin('t.TasksLabels tl')
          ->leftJoin('t.TasksTypes tt')
          ->leftJoin('t.TasksGroups tg')
          ->leftJoin('t.ProjectsPhases pp')
          ->leftJoin('t.Versions v')
          ->leftJoin('t.Projects p')
          ->leftJoin('t.Users')                              
          ->whereIn('t.id',explode(',',$request->getParameter('selected_items')));
          
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
      
      
      $tree_order = false;
                        
      if($request->hasParameter('projects_id'))
      {
        $q = app::addListingOrder($q,'tasks',$this->getUser(), (int)$request->getParameter('projects_id'));
      }
      else
      {
        $q->orderBy('LTRIM(p.name), ts.group desc, ts.sort_order, LTRIM(ts.name), LTRIM(t.name)');
      }   
                
          
      $tasks = $q->fetchArray();
            
      
      $totals = array();
      $projects_totals = array();
      $current_project_id = 0;
          
      foreach($tasks as $t)
      {
        $content = '';
        
        //
        if($current_project_id==0) $current_project_id = $t['projects_id'];
         
        if($current_project_id!=$t['projects_id'])
        {
          
           //adding totals
          if(isset($projects_totals[$current_project_id]))
          {           
            foreach($fields as $f)
            {
              $v = '';          
              
              if(in_array($f,array('estimated_time','work_hours')))
              {            
                $v = $projects_totals[$current_project_id][$f];           
              }
              elseif(strstr($f,'extra_field_'))
              {          
                if(isset($projects_totals[$current_project_id][str_replace('extra_field_','',$f)])) $v = $projects_totals[$current_project_id][str_replace('extra_field_','',$f)];                        
              }
                      
              $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
            }
                      
            $content .= "\n\n";
          }          
          
          $current_project_id=$t['projects_id'];
        }
      
        //
        
      
        $ex_values = ExtraFieldsList::getValuesList($extra_fields,$t['id']);                
                                        
        foreach($fields as $f)
        {
          $v = '';          
          
          if(in_array($f,array('id','name','description')))
          {
            $v=str_replace(array("\n","\r","\n\r"),'',$t[$f]);
          }           
          elseif(in_array($f,array('id','description','estimated_time','work_hours')))
          {   
            
            if($f=='work_hours')
            {
              $v = TasksComments::getTotalWorkHours($t['id']);
            }
            else
            {       
              $v=$t[$f];
            }
            
            if(in_array($f,array('estimated_time','work_hours')))
            {
              if(!isset($totals[$f])) $totals[$f] = 0;
              if(!isset($projects_totals[$t['projects_id']][$f])) $projects_totals[$t['projects_id']][$f] = 0;
                                            
              $totals[$f]+=$v;
              $projects_totals[$t['projects_id']][$f]+=$v;
            }
          }
          elseif(in_array($f,array('start_date','due_date','created_at')))
          {            
            $v=app::dateTimeFormat($t[$f]);
          }
          elseif($f=='progress')
          {
            $v = (int)$t['progress'] . '%';
          }
          elseif(strstr($f,'extra_field_'))
          {
            if($ex = Doctrine_Core::getTable('ExtraFields')->find(str_replace('extra_field_','',$f)))
            {
              $v = ExtraFieldsList::renderFieldValueByType($ex,$ex_values,array(),true);
                            
              if(in_array($ex->getType(),array('number','formula')))
              {
                if(!isset($totals[$ex->getId()])) $totals[$ex->getId()] = 0;
                if(!isset($projects_totals[$t['projects_id']][$ex->getId()])) $projects_totals[$t['projects_id']][$ex->getId()] = 0;
                
                $totals[$ex->getId()]+= $v;
                $projects_totals[$t['projects_id']][$ex->getId()]+=$v;
              }
              
              $v = str_replace('<br>',', ',$v);
            }
          }
          elseif($f=='assigned_to')
          { 
            $v = Users::getNameById($t[$f],', ');            
          }
          elseif($f=='url')
          {
            $v = app::public_url('tasksComments/index?projects_id=' . $t['projects_id'] . '&tasks_id=' . $t['id']);
          }
          elseif($f=='actual_time')
          {
          
            if($t['work_hours']>0 and $t['togo_hours']==0)
            {
              $v = $t['work_hours'];
            }
            else
            {
              $v = (float)$t['estimated_time']-(float)$t['work_hours']+(float)$t['togo_hours'];
            }
            
            if(!isset($totals[$f])) $totals[$f] = 0;
              
            $totals[$f]+=$v;
            $projects_totals[$t['projects_id']][$f]+=$v;
          }
          else
          {            
            $v=app::getArrayName($t,$f);
          }
          
          $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
        }
        
        $content .= "\n";
      
        if($format=='csv')
        {
          echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
        }
        else
        {
          echo $content;
        }        
      }
      
      $content = '';
      
      //adding totals
      if(isset($projects_totals[$current_project_id]) and !$request->hasParameter('projects_id'))
      {
        foreach($fields as $f)
        {
          $v = '';          
          
          if(in_array($f,array('estimated_time','work_hours')))
          {            
            $v = $projects_totals[$current_project_id][$f];             
          }
          elseif(strstr($f,'extra_field_'))
          {          
            if(isset($projects_totals[$current_project_id][str_replace('extra_field_','',$f)])) $v = $projects_totals[$current_project_id][str_replace('extra_field_','',$f)];            
          }
                  
          $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
        }
                  
        $content .= "\n\n";
      }
      
      foreach($fields as $f)
      {
        $v = '';          
        
        if(in_array($f,array('estimated_time','work_hours')))
        {            
          $v = $totals[$f]; 
        }
        elseif(strstr($f,'extra_field_'))
        {          
          if(isset($totals[str_replace('extra_field_','',$f)])) $v = $totals[str_replace('extra_field_','',$f)];          
        }
                
        $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
      }
      
      $content .= "\n";
      
      if($format=='csv')
      {
        echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
      }
      else
      {
        echo $content;
      }
                        
      exit(); 
    }
  }
  
  public function executeMultipleEdit(sfWebRequest $request)
  {           
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkTasksAccess('edit',false,$this->projects);
    }
    else
    {
      $this->checkTasksAccess('edit');
    }
    
    $this->fields = array();
    $this->numeric_fields = array();
    
    $choices = app::getItemsChoicesByTable('TasksPriority',true); 
    if(count($choices)>1) $this->fields['tasks_priority_id'] = array('title'=>t::__('Priority'),'choices'=>$choices);
    
    $choices = app::getItemsChoicesByTable('TasksLabels',true);
    if(count($choices)>1) $this->fields['tasks_label_id'] = array('title'=>t::__('Label'),'choices'=>$choices);
    
    $choices = app::getItemsChoicesByTable('TasksStatus',true); 
    if(count($choices)>1) $this->fields['tasks_status_id'] = array('title'=>t::__('Status'),'choices'=>$choices);
    
    $choices = app::getItemsChoicesByTable('TasksTypes',true); 
    if(count($choices)>1) $this->fields['tasks_type_id'] = array('title'=>t::__('Type'),'choices'=>$choices);
    
    if($request->hasParameter('projects_id'))
    {
      $choices = app::getItemsChoicesByTable('TasksGroups',true,$request->getParameter('projects_id')); 
      if(count($choices)>1) $this->fields['tasks_groups_id'] = array('title'=>t::__('Group'),'choices'=>$choices);
      
      $choices = app::getItemsChoicesByTable('Versions',true,$request->getParameter('projects_id')); 
      if(count($choices)>1) $this->fields['versions_id'] = array('title'=>t::__('Version'),'choices'=>$choices);
      
      $choices = app::getItemsChoicesByTable('ProjectsPhases',true,$request->getParameter('projects_id'));
      if(count($choices)>1) $this->fields['projects_phases_id'] = array('title'=>t::__('Phase'),'choices'=>$choices);
    }
    
    $this->fields['progress'] = array('title'=>t::__('Progress'),'choices'=>Tasks::getProgressChoices());
    
    $extra_fields = ExtraFieldsList::getFieldsByType('tasks',$this->getUser(),false,array('all'=>true));
    

    
    
    $this->numeric_fields['estimated_time'] = array('title'=>t::__('Estimated Time'));
    
    foreach($extra_fields as $v)
    {
      if(in_array($v['type'],array('number')))
      {                        
        $this->numeric_fields['extra_field_' . $v['id']] = array('title'=>$v['name']);        
      }
    }        
    
    if($request->getParameter('fields'))
    {
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
      
      
      foreach($request->getParameter('fields') as $key=>$value)
      {
        if(strlen($value)==0 and !is_array($value)) continue;
        
    
        if($key=='tasks_status_id')
        {
          foreach(explode(',',$request->getParameter('selected_items')) as $pid)
          {
            if($p = Doctrine_Core::getTable('Tasks')->find($pid))
            {
              if($p->getTasksStatusId()!=$value)
              {
                $p->setTasksStatusId($value);                  
                                  
                if(in_array($value,app::getStatusByGroup('closed','TasksStatus')))
                {
                  $p->setClosedDate(date('Y-m-d H:i:s'));                          
                }
                
                if(!in_array($value,app::getStatusByGroup('closed','TasksStatus')))
                {
                  $p->setClosedDate(null);                          
                }
                                                  
                $p->save();
                
                if(strlen($p->getAssignedTo())>0)
                {
                  Tasks::sendNotification($this, $p,array('status'=>explode(',',$p->getAssignedTo())),$this->getUser());
                }
                
                $c = new TasksComments;
                $c->setTasksStatusId($value);
                $c->setTasksId($pid);
                $c->setCreatedAt(date('Y-m-d H:i:s'));
                $c->setCreatedBy($this->getUser()->getAttribute('id'));
                $c->save();
                
                
              }
            }
          }
        }
        elseif($key=='estimated_time')
        {
          $action_str = (float)$value;
          if(in_array(substr($value,0,1),array('+','-','*','/')))
          {                            
            $action_str = 'estimated_time ' . substr($value,0,1) . ' ' . (float)substr($value,1);
                                         
            Doctrine_Query::create()
            ->update('Tasks')
            ->set('estimated_time',0)                  
            ->whereIn('id', explode(',',$request->getParameter('selected_items')))
            ->addWhere('estimated_time is null or length(estimated_time)=0')
            ->execute();                            
          }
                              
          Doctrine_Query::create()
            ->update('Tasks')
            ->set('estimated_time',$action_str)                  
            ->whereIn('id', explode(',',$request->getParameter('selected_items')))
            ->execute();
        }
        elseif($key=='assigned_to')
        {          
          $tasks_list = Doctrine_Core::getTable('Tasks')->createQuery()->whereIn('id',explode(',',$request->getParameter('selected_items')))->execute();
          
          foreach($tasks_list as $tasks)
          {
            $tasks->setAssignedTo(implode(',',$value));
            $tasks->save();
            
            Tasks::sendNotification($this, $tasks,array('new'=>$value),$this->getUser());
            
            $c = new TasksComments;
            $c->setDescription(t::__('Assigned To') . ': ' .  Users::getNameById(implode(',',$value),', '));
            $c->setTasksId($tasks->getId());
            $c->setCreatedAt(date('Y-m-d H:i:s'));
            $c->setCreatedBy($this->getUser()->getAttribute('id'));
            $c->save();
                        
            $tasks->save();
          }                        
        }
        else
        {        
          Doctrine_Query::create()
            ->update('Tasks')
            ->set($key,$value)                  
            ->whereIn('id', explode(',',$request->getParameter('selected_items')))
            ->execute();
                          
        }
        
      }
      
      $this->redirect_to($request->getParameter('redirect_to'),$request->getParameter('projects_id'),$request->getParameter('tasks_id'));
    }        
  }
  
  public function executeMoveTo(sfWebRequest $request)
  {  
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkTasksAccess('edit',false,$this->projects);
    }
    else
    {
      $this->checkTasksAccess('edit');
    }
    
    if($request->getParameter('move_to')>0)
    {
      Doctrine_Query::create()
              ->update('Tasks')
              ->set('projects_id',$request->getParameter('move_to'))
              ->set('tasks_groups_id','NULL')                  
              ->set('versions_id','NULL')              
              ->set('projects_phases_id','NULL')
              ->whereIn('id', explode(',',$request->getParameter('selected_items')))
              ->execute();
                                
      $this->redirect_to($request->getParameter('redirect_to'),$request->getParameter('move_to'),$request->getParameter('tasks_id'));
    }    
  } 
 
}
