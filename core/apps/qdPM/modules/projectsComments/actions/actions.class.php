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
 * projectsComments actions.
 *
 * @package    sf_sandbox
 * @subpackage projectsComments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectsCommentsActions extends sfActions
{
  protected function checkProjectsAccess($projects)
  {
    Users::checkAccess($this,'view','projects',$this->getUser(),$projects->getId());
    Projects::checkViewOwnAccess($this,$this->getUser(),$projects);
  }
  
  function checkViewOwnAccess($comments,$projects)
  {
    if(Users::hasAccess('view_own','projectsComments',$this->getUser(),$projects->getId()))
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
    $this->checkProjectsAccess($this->projects);
        
    $this->projects_comments = Doctrine_Core::getTable('ProjectsComments')
      ->createQuery('pc')                        
      ->leftJoin('pc.Users u')
      ->addWhere('projects_id=?',$request->getParameter('projects_id'))
      
      ->orderBy('created_at desc')
      ->fetchArray();
      
    app::setPageTitle($this->projects->getName(),$this->getResponse());
  }

  public function executeNew(sfWebRequest $request)
  {
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkProjectsAccess($this->projects);
    Users::checkAccess($this,'insert','projectsComments',$this->getUser(),$this->projects->getId());
    
    $this->form = new ProjectsCommentsForm(null, array('projects'=>$this->projects,'sf_user'=>$this->getUser()));
    
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkProjectsAccess($this->projects);
    Users::checkAccess($this,'insert','projectsComments',$this->getUser(),$this->projects->getId());
    
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ProjectsCommentsForm(null, array('projects'=>$this->projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkProjectsAccess($this->projects);
    Users::checkAccess($this,'edit','projectsComments',$this->getUser(),$this->projects->getId());
  
    $this->forward404Unless($projects_comments = Doctrine_Core::getTable('ProjectsComments')->find(array($request->getParameter('id'))), sprintf('Object projects_comments does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProjectsCommentsForm($projects_comments,array('projects'=>$projects_comments->getProjects(),'sf_user'=>$this->getUser()));
    
    $this->checkViewOwnAccess($projects_comments,$this->projects);    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkProjectsAccess($this->projects);
    Users::checkAccess($this,'edit','projectsComments',$this->getUser(),$this->projects->getId());
        
    $this->forward404Unless($projects_comments = Doctrine_Core::getTable('ProjectsComments')->find(array($request->getParameter('id'))), sprintf('Object projects_comments does not exist (%s).', $request->getParameter('id')));
   
    $this->checkViewOwnAccess($projects_comments,$this->projects);
   
    $this->form = new ProjectsCommentsForm($projects_comments,array('projects'=>$projects_comments->getProjects(),'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkProjectsAccess($projects);
    Users::checkAccess($this,'delete','projectsComments',$this->getUser(),$projects->getId());

    $this->forward404Unless($projects_comments = Doctrine_Core::getTable('ProjectsComments')->find(array($request->getParameter('id'))), sprintf('Object projects_comments does not exist (%s).', $request->getParameter('id')));
   
    $this->checkViewOwnAccess($projects_comments,$projects);
    
    $projects_comments->delete();
    Attachments::resetAttachments();
        
    $this->redirect('projectsComments/index?projects_id='.$projects_comments->getProjectsId());
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {  
      if($form->getObject()->isNew())
      {    
        $project = Doctrine_Core::getTable('Projects')->find($request->getParameter('projects_id'));            
        if($request->getParameter('projects_types_id')>0){ $project->setProjectsTypesId($request->getParameter('projects_types_id')); }       
        if($request->getParameter('projects_status_id')>0){  $project->setProjectsStatusId($request->getParameter('projects_status_id'));}
        $project->save();
      }
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
                        
      $form->protectFieldsValue();
      
      $projects_comments = $form->save();
                              
      Attachments::insertAttachments($request->getFiles(),'projectsComments',$projects_comments->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      ProjectsComments::sendNotification($this,$projects_comments,$this->getUser());
                  
      $this->redirect('projectsComments/index?projects_id='.$projects_comments->getProjectsId());
    }
  }
  
  public function executeInfo(sfWebRequest $request) 
  {
    if(!$this->c = Doctrine_Core::getTable('ProjectsComments')->find($request->getParameter('id')))
    {
      exit();
    }
    
    $this->checkProjectsAccess($this->c->getProjects());
    Users::checkAccess($this,'view','projectsComments',$this->getUser(),$this->c->getProjects()->getId());
    
  }
}
