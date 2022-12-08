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
