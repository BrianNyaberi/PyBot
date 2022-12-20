<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * discussionsReports actions.
 *
 * @package    sf_sandbox
 * @subpackage discussionsReports
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class discussionsReportsActions extends sfActions
{
  public function checkAccess($reports,$check_view = false)
  {
    if($reports->getUsersId()!=$this->getUser()->getAttribute('id'))
    {
      $this->redirect('accessForbidden/index');
    }
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->discussions_reportss = Doctrine_Core::getTable('DiscussionsReports')
      ->createQuery()
      ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))      
      ->orderBy('sort_order, name')
      ->execute();
      
    app::setPageTitle('Discussions Reports',$this->getResponse());
  }
  
  public function executeView(sfWebRequest $request)
  {
    $this->forward404Unless($this->discussions_reports = Doctrine_Core::getTable('DiscussionsReports')->find(array($request->getParameter('id'))), sprintf('Object discussions_reports does not exist (%s).', $request->getParameter('id')));
    
    $this->checkAccess($this->discussions_reports, true);
    
    app::setPageTitle($this->discussions_reports->getName(),$this->getResponse());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new DiscussionsReportsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DiscussionsReportsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($discussions_reports = Doctrine_Core::getTable('DiscussionsReports')->find(array($request->getParameter('id'))), sprintf('Object discussions_reports does not exist (%s).', $request->getParameter('id')));
    $this->checkAccess($discussions_reports);
    $this->form = new DiscussionsReportsForm($discussions_reports);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($discussions_reports = Doctrine_Core::getTable('DiscussionsReports')->find(array($request->getParameter('id'))), sprintf('Object discussions_reports does not exist (%s).', $request->getParameter('id')));
    $this->checkAccess($discussions_reports);
    $this->form = new DiscussionsReportsForm($discussions_reports);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($discussions_reports = Doctrine_Core::getTable('DiscussionsReports')->find(array($request->getParameter('id'))), sprintf('Object discussions_reports does not exist (%s).', $request->getParameter('id')));
    $this->checkAccess($discussions_reports);
    $discussions_reports->delete();

    switch($request->getParameter('redirect_to'))
    {
      case 'commonReports': $this->redirect('commonReports/index');
        break;
      default: $this->redirect('discussionsReports/index');
        break;
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {                  
      $form->setFieldValue('discussions_status_id',$form['discussions_status_id']->getValue());            
                                                      
      $form->setFieldValue('projects_status_id',$form['projects_status_id']->getValue());            
      $form->setFieldValue('projects_type_id',$form['projects_type_id']->getValue());                              
      $form->setFieldValue('projects_id',$form['projects_id']->getValue());    
      
      $form->protectFieldsValue();
                                     
      $discussions_reports = $form->save();

      switch($request->getParameter('redirect_to'))
      {      
        case 'view': $this->redirect('discussionsReports/view?id=' . $discussions_reports->getId());
          break;
        default: $this->redirect('discussionsReports/index');
          break;
      }     
    }
  }
}
