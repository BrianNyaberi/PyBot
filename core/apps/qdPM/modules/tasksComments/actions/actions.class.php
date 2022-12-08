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
 * tasksComments actions.
 *
 * @package    sf_sandbox
 * @subpackage tasksComments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tasksCommentsActions extends sfActions
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
  }
  
  protected function checkViewOwnAccess($comments,$projects)
  {
    if(Users::hasAccess('view_own','tasksComments',$this->getUser(),$projects->getId()))
    {
      if($comments->getCreatedBy()!=$this->getUser()->getAttribute('id'))
      {
        $this->redirect('accessForbidden/index');
      }
    }
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('tasks_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);
    
    if(!$this->getUser()->hasAttribute('tasks_filter' . $request->getParameter('projects_id')))
    {
      $this->getUser()->setAttribute('tasks_filter' . $request->getParameter('projects_id'), Tasks::getDefaultFilter($request,$this->getUser()));
    }     
            
    $this->tasks_comments = Doctrine_Core::getTable('TasksComments')
      ->createQuery('tc')      
      ->leftJoin('tc.Users u')
      ->addWhere('tc.tasks_id=?',$request->getParameter('tasks_id'))      
      ->orderBy('tc.created_at desc')
      ->fetchArray();
    
          
    $this->more_actions = $this->getMoreActions($request);
                         
    app::setPageTitle( t::__('Task') . ' | '. ($this->tasks->getTasksLabelId()>0 ? $this->tasks->getTasksLabels()->getName(). ': ':'') . $this->tasks->getName(),$this->getResponse());  
  }
  
  protected function getMoreActions(sfWebRequest $request)
  {
    $more_actions = array();    
    $s = array();
            
    if(Users::hasAccess('edit','tasks',$this->getUser(),$request->getParameter('projects_id')))
    {      
      $s[] = array('title'=>t::__('Move To'),'url'=>'tasks/moveTo?tasks_id=' . $request->getParameter('tasks_id') . '&projects_id=' . $request->getParameter('projects_id') . '&redirect_to=tasksComments','modalbox'=>true);
    }
    
    if(Users::hasAccess('delete','tasks',$this->getUser(),$request->getParameter('projects_id')))
    {
      $s[] = array('title'=>t::__('Delete'),'url'=>'tasks/delete?id=' . $request->getParameter('tasks_id') . '&projects_id=' . $request->getParameter('projects_id'),'confirm'=>true);      
    }
    
    if(count($s)>0)
    {
      $more_actions[] = array('title'=>t::__('More Actions'),'submenu'=>$s);
    }
    
    return $more_actions; 
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('tasks_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tasks_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);
    Users::checkAccess($this,'insert','tasksComments',$this->getUser(),$this->projects->getId());
    
    $this->form = new TasksCommentsForm(null, array('tasks'=>$this->tasks));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('tasks_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tasks_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);
    Users::checkAccess($this,'insert','tasksComments',$this->getUser(),$this->projects->getId());
    
    $this->form = new TasksCommentsForm(null, array('tasks'=>$this->tasks));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($tasks_comments = Doctrine_Core::getTable('TasksComments')->find(array($request->getParameter('id'))), sprintf('Object tasks_comments does not exist (%s).', $request->getParameter('id')));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('tasks_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tasks_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);                             
    Users::checkAccess($this,'edit','tasksComments',$this->getUser(),$this->projects->getId());
    $this->checkViewOwnAccess($tasks_comments,$this->projects);
    
    $this->form = new TasksCommentsForm($tasks_comments, array('tasks'=>$this->tasks));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($tasks_comments = Doctrine_Core::getTable('TasksComments')->find(array($request->getParameter('id'))), sprintf('Object tasks_comments does not exist (%s).', $request->getParameter('id')));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('tasks_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tasks_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);
    Users::checkAccess($this,'edit','tasksComments',$this->getUser(),$this->projects->getId());
    $this->checkViewOwnAccess($tasks_comments,$this->projects);
    
    
    $this->form = new TasksCommentsForm($tasks_comments, array('tasks'=>$this->tasks));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($tasks_comments = Doctrine_Core::getTable('TasksComments')->find(array($request->getParameter('id'))), sprintf('Object tasks_comments does not exist (%s).', $request->getParameter('id')));
        
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->tasks = Doctrine_Core::getTable('Tasks')->createQuery()->addWhere('id=?',$request->getParameter('tasks_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tasks_id')));    
    $this->checkProjectsAccess($this->projects);
    $this->checkTasksAccess('view',$this->tasks, $this->projects);
    
    Users::checkAccess($this,'delete','tasksComments',$this->getUser(),$this->projects->getId());
    $this->checkViewOwnAccess($tasks_comments,$this->projects);
                
    $tasks_comments->delete();
    Attachments::resetAttachments();
    
  

    $this->redirect('tasksComments/index?projects_id=' . $request->getParameter('projects_id') . '&tasks_id=' . $request->getParameter('tasks_id'));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if($form->getObject()->isNew())
      {
        $tasks = Doctrine_Core::getTable('Tasks')->find($request->getParameter('tasks_id'));
        if($form->getValue('tasks_priority_id')>0){ $tasks->setTasksPriorityId($form->getValue('tasks_priority_id')); } else { unset($form['tasks_priority_id']); }            
        if($request->getParameter('tasks_labels_id')>0){ $tasks->setTasksLabelId($request->getParameter('tasks_labels_id')); }
        if($request->getParameter('tasks_types_id')>0){ $tasks->setTasksTypeId($request->getParameter('tasks_types_id')); }             
        if(strlen($form->getValue('due_date'))>0){ $tasks->setDueDate($form->getValue('due_date')); } else { unset($form['due_date']); }
        if($request->getParameter('progress')>0){ $tasks->setProgress($request->getParameter('progress')); } 
             
        if($form->getValue('tasks_status_id')>0)
        { 
          $tasks->setTasksStatusId($form->getValue('tasks_status_id'));
          
          if(in_array($form->getValue('tasks_status_id'),app::getStatusByGroup('closed','TasksStatus')))
          {
            $tasks->setClosedDate(date('Y-m-d H:i:s'));
            $tasks->save();      
          }
          
          if(!in_array($form->getValue('tasks_status_id'),app::getStatusByGroup('closed','TasksStatus')))
          {
            $tasks->setClosedDate(null);
            $tasks->save();      
          } 
        } 
        else 
        { 
          unset($form['tasks_status_id']); 
        }
      
                                        
        $tasks->save();
      }
      
      if($form->getObject()->isNew() and sfConfig::get('app_allow_adit_tasks_comments_date')!='on'){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
    
      $form->protectFieldsValue();
      
      $tasks_comments = $form->save();
                  
      Attachments::insertAttachments($request->getFiles(),'comments',$tasks_comments->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      TasksComments::sendNotification($this,$tasks_comments,$this->getUser());
      
  
      $this->redirect('tasksComments/index?projects_id=' . $request->getParameter('projects_id') . '&tasks_id=' . $request->getParameter('tasks_id'));
    }
  }
      
  public function executeInfo(sfWebRequest $request) 
  {
    if(!$this->c = Doctrine_Core::getTable('TasksComments')->find($request->getParameter('id')))
    {
      exit();
    }
    
    $this->checkProjectsAccess($this->c->getTasks()->getProjects());
    Users::checkAccess($this,'view','tasksComments',$this->getUser(),$this->c->getTasks()->getProjects()->getId());    
  }
   
}
