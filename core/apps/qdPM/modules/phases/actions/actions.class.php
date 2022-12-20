<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * phases actions.
 *
 * @package    sf_sandbox
 * @subpackage phases
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class phasesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->phasess = Doctrine_Core::getTable('Phases')
      ->createQuery('a')
      ->orderBy('name')
      ->execute();
      
    app::setPageTitle('Default Phases',$this->getResponse());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PhasesForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PhasesForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($phases = Doctrine_Core::getTable('Phases')->find(array($request->getParameter('id'))), sprintf('Object phases does not exist (%s).', $request->getParameter('id')));
    $this->form = new PhasesForm($phases);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($phases = Doctrine_Core::getTable('Phases')->find(array($request->getParameter('id'))), sprintf('Object phases does not exist (%s).', $request->getParameter('id')));
    $this->form = new PhasesForm($phases);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($phases = Doctrine_Core::getTable('Phases')->find(array($request->getParameter('id'))), sprintf('Object phases does not exist (%s).', $request->getParameter('id')));
    $phases->delete();

    $this->redirect('phases/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
        $form->protectFieldsValue();
        
      $phases = $form->save();

      $this->redirect('phases/index');
    }
  }
}
