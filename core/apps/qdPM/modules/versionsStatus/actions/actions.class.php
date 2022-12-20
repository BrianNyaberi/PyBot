<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * versionsStatus actions.
 *
 * @package    sf_sandbox
 * @subpackage versionsStatus
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class versionsStatusActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Versions Status',$this->getResponse());
    
    $this->versions_statuss = Doctrine_Core::getTable('VersionsStatus')
      ->createQuery('a')
      ->orderBy('sort_order, name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new VersionsStatusForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new VersionsStatusForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($versions_status = Doctrine_Core::getTable('VersionsStatus')->find(array($request->getParameter('id'))), sprintf('Object versions_status does not exist (%s).', $request->getParameter('id')));
    $this->form = new VersionsStatusForm($versions_status);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($versions_status = Doctrine_Core::getTable('VersionsStatus')->find(array($request->getParameter('id'))), sprintf('Object versions_status does not exist (%s).', $request->getParameter('id')));
    $this->form = new VersionsStatusForm($versions_status);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($versions_status = Doctrine_Core::getTable('VersionsStatus')->find(array($request->getParameter('id'))), sprintf('Object versions_status does not exist (%s).', $request->getParameter('id')));
    $versions_status->delete();

    $this->redirect('versionsStatus/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $versions_status = $form->save();
      
      if($versions_status->getDefaultValue()==1)
      {
        app::resetCfgDefaultValue($versions_status->getId(),'VersionsStatus');
      }

      $this->redirect('versionsStatus/index');
    }
  }
}
