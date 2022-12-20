<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * departments actions.
 *
 * @package    sf_sandbox
 * @subpackage departments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class departmentsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->departmentss = Doctrine_Core::getTable('Departments')
      ->createQuery()
      ->orderBy('sort_order,name')
      ->execute();
      
    app::setPageTitle('Departments',$this->getResponse());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DepartmentsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DepartmentsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($departments = Doctrine_Core::getTable('Departments')->find(array($request->getParameter('id'))), sprintf('Object departments does not exist (%s).', $request->getParameter('id')));
    $this->form = new DepartmentsForm($departments);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($departments = Doctrine_Core::getTable('Departments')->find(array($request->getParameter('id'))), sprintf('Object departments does not exist (%s).', $request->getParameter('id')));
    $this->form = new DepartmentsForm($departments);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($departments = Doctrine_Core::getTable('Departments')->find(array($request->getParameter('id'))), sprintf('Object departments does not exist (%s).', $request->getParameter('id')));
    $departments->delete();

    $this->redirect('departments/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {              
      $departments = $form->save();

      $this->redirect('departments/index');
    }
  }
}
