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
 * tasksGroups actions.
 *
 * @package    sf_sandbox
 * @subpackage tasksGroups
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tasksGroupsActions extends sfActions
{
  protected function checkTasksAccess($projects)
  {
    Projects::checkViewOwnAccess($this,$this->getUser(),$projects);
    Users::checkAccess($this,'insert','tasks',$this->getUser(),$projects->getId());
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Tasks Groups',$this->getResponse());
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkTasksAccess($this->projects);
        
    $this->tasks_groupss = Doctrine_Core::getTable('TasksGroups')
      ->createQuery('a')
      ->addWhere('projects_id=?',$this->projects->getId())
      ->orderBy('name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkTasksAccess($this->projects);
    
    $this->form = new TasksGroupsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkTasksAccess($this->projects);

    $this->form = new TasksGroupsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkTasksAccess($this->projects);
    
    $this->forward404Unless($tasks_groups = Doctrine_Core::getTable('TasksGroups')->find(array($request->getParameter('id'))), sprintf('Object tasks_groups does not exist (%s).', $request->getParameter('id')));
    $this->form = new TasksGroupsForm($tasks_groups);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkTasksAccess($this->projects);
    
    $this->forward404Unless($tasks_groups = Doctrine_Core::getTable('TasksGroups')->find(array($request->getParameter('id'))), sprintf('Object tasks_groups does not exist (%s).', $request->getParameter('id')));
    $this->form = new TasksGroupsForm($tasks_groups);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->checkTasksAccess($this->projects);

    $this->forward404Unless($tasks_groups = Doctrine_Core::getTable('TasksGroups')->find(array($request->getParameter('id'))), sprintf('Object tasks_groups does not exist (%s).', $request->getParameter('id')));
    $tasks_groups->delete();

    $this->redirect('tasksGroups/index?projects_id=' . $request->getParameter('projects_id'));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
        $form->protectFieldsValue();
        
      $tasks_groups = $form->save();

      $this->redirect('tasksGroups/index?projects_id=' . $request->getParameter('projects_id'));
    }
  }
}
