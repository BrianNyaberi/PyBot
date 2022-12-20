<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * discussionsStatus actions.
 *
 * @package    sf_sandbox
 * @subpackage discussionsStatus
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class discussionsStatusActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Discussions Status',$this->getResponse());
    
    $this->discussions_statuss = Doctrine_Core::getTable('DiscussionsStatus')
      ->createQuery('a')
      ->orderBy('sort_order, name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DiscussionsStatusForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DiscussionsStatusForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($discussions_status = Doctrine_Core::getTable('DiscussionsStatus')->find(array($request->getParameter('id'))), sprintf('Object discussions_status does not exist (%s).', $request->getParameter('id')));
    $this->form = new DiscussionsStatusForm($discussions_status);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($discussions_status = Doctrine_Core::getTable('DiscussionsStatus')->find(array($request->getParameter('id'))), sprintf('Object discussions_status does not exist (%s).', $request->getParameter('id')));
    $this->form = new DiscussionsStatusForm($discussions_status);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($discussions_status = Doctrine_Core::getTable('DiscussionsStatus')->find(array($request->getParameter('id'))), sprintf('Object discussions_status does not exist (%s).', $request->getParameter('id')));
    $discussions_status->delete();

    $this->redirect('discussionsStatus/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $discussions_status = $form->save();
      
      if($discussions_status->getDefaultValue()==1)
      {
        app::resetCfgDefaultValue($discussions_status->getId(),'DiscussionsStatus');
      }

      $this->redirect('discussionsStatus/index');
    }
  }
}
