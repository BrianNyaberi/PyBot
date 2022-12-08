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
 * ticketsTypes actions.
 *
 * @package    sf_sandbox
 * @subpackage ticketsTypes
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketsTypesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('Tickets Types',$this->getResponse());
    
    $this->tickets_typess = Doctrine_Core::getTable('TicketsTypes')
      ->createQuery('a')
      ->orderBy('sort_order, name')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new TicketsTypesForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new TicketsTypesForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($tickets_types = Doctrine_Core::getTable('TicketsTypes')->find(array($request->getParameter('id'))), sprintf('Object tickets_types does not exist (%s).', $request->getParameter('id')));
    $this->form = new TicketsTypesForm($tickets_types);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($tickets_types = Doctrine_Core::getTable('TicketsTypes')->find(array($request->getParameter('id'))), sprintf('Object tickets_types does not exist (%s).', $request->getParameter('id')));
    $this->form = new TicketsTypesForm($tickets_types);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($tickets_types = Doctrine_Core::getTable('TicketsTypes')->find(array($request->getParameter('id'))), sprintf('Object tickets_types does not exist (%s).', $request->getParameter('id')));
    $tickets_types->delete();

    $this->redirect('ticketsTypes/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      
      $tickets_types = $form->save();
      
      $this->redirect('ticketsTypes/index');
    }
  }
}
