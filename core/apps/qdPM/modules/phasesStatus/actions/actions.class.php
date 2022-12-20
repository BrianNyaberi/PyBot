<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * phasesStatus actions.
 *
 * @package    sf_sandbox
 * @subpackage phasesStatus
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class phasesStatusActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Phase Status',$this->getResponse());
    
    $this->phases_statuss = Doctrine_Core::getTable('PhasesStatus')
      ->createQuery('a')
      ->orderBy('sort_order, name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PhasesStatusForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PhasesStatusForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($phases_status = Doctrine_Core::getTable('PhasesStatus')->find(array($request->getParameter('id'))), sprintf('Object phases_status does not exist (%s).', $request->getParameter('id')));
    $this->form = new PhasesStatusForm($phases_status);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($phases_status = Doctrine_Core::getTable('PhasesStatus')->find(array($request->getParameter('id'))), sprintf('Object phases_status does not exist (%s).', $request->getParameter('id')));
    $this->form = new PhasesStatusForm($phases_status);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($phases_status = Doctrine_Core::getTable('PhasesStatus')->find(array($request->getParameter('id'))), sprintf('Object phases_status does not exist (%s).', $request->getParameter('id')));
    $phases_status->delete();

    $this->redirect('phasesStatus/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $phases_status = $form->save();

      $this->redirect('phasesStatus/index');
    }
  }
}
