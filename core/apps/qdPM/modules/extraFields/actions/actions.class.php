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
 * extraFields actions.
 *
 * @package    sf_sandbox
 * @subpackage extraFields
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class extraFieldsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('bind_type'), 'bind_type is not defined');
        
    $this->extra_fieldss = Doctrine_Core::getTable('ExtraFields')
      ->createQuery('ef')
      ->addWhere('ef.bind_type=?',$request->getParameter('bind_type','projects'))
      ->orderBy('ef.sort_order, ef.name')
      ->execute();
      
    app::setPageTitle('Extra Fields',$this->getResponse());
  }
  

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('bind_type'), 'bind_type is not defined');
    
    $this->form = new ExtraFieldsForm(null, array('bind_type'=>$request->getParameter('bind_type')));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('bind_type'), 'bind_type is not defined');
    
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ExtraFieldsForm(null, array('bind_type'=>$request->getParameter('bind_type')));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('bind_type'), 'bind_type is not defined');
    
    $this->forward404Unless($extra_fields = Doctrine_Core::getTable('ExtraFields')->find(array($request->getParameter('id'))), sprintf('Object extra_fields does not exist (%s).', $request->getParameter('id')));
    $this->form = new ExtraFieldsForm($extra_fields, array('bind_type'=>$request->getParameter('bind_type')));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('bind_type'), 'bind_type is not defined');
    
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($extra_fields = Doctrine_Core::getTable('ExtraFields')->find(array($request->getParameter('id'))), sprintf('Object extra_fields does not exist (%s).', $request->getParameter('id')));
    $this->form = new ExtraFieldsForm($extra_fields, array('bind_type'=>$request->getParameter('bind_type')));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $this->forward404Unless($request->hasParameter('bind_type'), 'bind_type is not defined');

    $this->forward404Unless($extra_fields = Doctrine_Core::getTable('ExtraFields')->find(array($request->getParameter('id'))), sprintf('Object extra_fields does not exist (%s).', $request->getParameter('id')));
    $extra_fields->delete();

    $this->redirect('extraFields/index?bind_type='.$request->getParameter('bind_type'));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
            
      $extra_fields = $form->save();

      $this->redirect('extraFields/index?bind_type='.$request->getParameter('bind_type'));
    }
  }
  
  public function executeMultipleEdit(sfWebRequest $request)
  {
    if($request->hasParameter('selected_items'))
    {
      foreach(explode(',',$request->getParameter('selected_items')) as $id)
      {
        if($ef = Doctrine_Core::getTable('ExtraFields')->find($id))
        {          
          if(strlen($request->getParameter('in_listing'))>0)
          {
            $ef->setDisplayInList($request->getParameter('in_listing'));
          }        
          
          if(strlen($request->getParameter('active'))>0)
          {
            $ef->setActive($request->getParameter('active'));
          }
          
          $ef->save();
        }
      }
      
      $this->redirect('extraFields/index?bind_type='.$request->getParameter('bind_type'));
    }
  }
}
