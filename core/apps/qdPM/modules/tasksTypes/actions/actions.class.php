<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * tasksTypes actions.
 *
 * @package    sf_sandbox
 * @subpackage tasksTypes
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tasksTypesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Tasks Types',$this->getResponse());
    
    $this->tasks_typess = Doctrine_Core::getTable('TasksTypes')
      ->createQuery('a')
      ->orderBy('sort_order, name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new TasksTypesForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new TasksTypesForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($tasks_types = Doctrine_Core::getTable('TasksTypes')->find(array($request->getParameter('id'))), sprintf('Object tasks_types does not exist (%s).', $request->getParameter('id')));
    $this->form = new TasksTypesForm($tasks_types);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($tasks_types = Doctrine_Core::getTable('TasksTypes')->find(array($request->getParameter('id'))), sprintf('Object tasks_types does not exist (%s).', $request->getParameter('id')));
    $this->form = new TasksTypesForm($tasks_types);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($tasks_types = Doctrine_Core::getTable('TasksTypes')->find(array($request->getParameter('id'))), sprintf('Object tasks_types does not exist (%s).', $request->getParameter('id')));
    $tasks_types->delete();

    $this->redirect('tasksTypes/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      
      $tasks_types = $form->save();
      
      if($tasks_types->getDefaultValue()==1)
      {
        app::resetCfgDefaultValue($tasks_types->getId(),'TasksTypes');
      }

      $this->redirect('tasksTypes/index');
    }
  }
}
