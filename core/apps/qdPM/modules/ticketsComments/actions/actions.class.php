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
 * ticketsComments actions.
 *
 * @package    sf_sandbox
 * @subpackage ticketsComments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketsCommentsActions extends sfActions
{
  protected function checkProjectsAccess($projects)
  {    
    Projects::checkViewOwnAccess($this,$this->getUser(),$projects);    
  }
  
  protected function checkTicketsAccess($access,$tickets=false,$projects=false)
  {
    if($projects)
    {
      Users::checkAccess($this,$access,'tickets',$this->getUser(),$projects->getId());
      if($tickets)
      {
        Tickets::checkViewOwnAccess($this,$this->getUser(),$tickets,$projects);
      }
    }
    elseif($tickets)
    {
      Users::checkAccess($this,$access,'tickets',$this->getUser());
      Tickets::checkViewOwnAccess($this,$this->getUser(),$tickets);
    }
  }
  
  protected function checkViewOwnAccess($comments,$projects)
  {
    if(Users::hasAccess('view_own','ticketsComments',$this->getUser(),$projects->getId()))
    {
      if($comments->getCreatedBy()!=$this->getUser()->getAttribute('id'))
      {
        $this->redirect('accessForbidden/index');
      }
    }
  }
  public function executeIndex(sfWebRequest $request)
  {
        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('tickets_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',$this->tickets, $this->projects);
      
      if(!$this->getUser()->hasAttribute('tickets_filter' . $request->getParameter('projects_id')))
      {
        $this->getUser()->setAttribute('tickets_filter' . $request->getParameter('projects_id'), Tickets::getDefaultFilter($request,$this->getUser()));
      }
    }
    else
    {
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('tickets_id'))), sprintf('Object tasks does not exist (%s).', $request->getParameter('id')));
      $this->checkTicketsAccess('view',$this->tickets);
    }
  
    $this->tickets_comments = Doctrine_Core::getTable('TicketsComments')
      ->createQuery('tc')      
      ->leftJoin('tc.Users u')
      ->addWhere('tc.tickets_id=?',$request->getParameter('tickets_id'))      
      ->orderBy('tc.created_at desc')
      ->fetchArray();
      
    $this->more_actions = $this->getMoreActions($request);
    
    app::setPageTitle(t::__('Ticket') . ' | ' . ($this->tickets->getTicketsTypesId()>0 ? $this->tickets->getTicketsTypes()->getName(). ': ':'') . $this->tickets->getName(),$this->getResponse());
  }
  
  protected function getMoreActions(sfWebRequest $request)
  {
    $more_actions = array();    
    $s = array();
    
    if(Users::hasAccess('insert','tasks',$this->getUser(),$request->getParameter('projects_id')) and $request->getParameter('projects_id')>0)
    {
      $s[] = array('title'=>t::__('Related Tasks'),
                   'submenu'=>array( array('title'=>t::__('Add Task'),'url'=>'tasks/new?related_tickets_id=' . $request->getParameter('tickets_id') . '&projects_id=' . $request->getParameter('projects_id'),'modalbox'=>true),     
                                    ));
    }    
    
    if(Users::hasAccess('edit','tickets',$this->getUser(),$request->getParameter('projects_id')))
    {      
      $s[] = array('title'=>t::__('Move To'),'url'=>'tickets/moveTo?tickets_id=' . $request->getParameter('tickets_id') . '&projects_id=' . $request->getParameter('projects_id') . '&redirect_to=ticketsComments','modalbox'=>true);
    }
    
    if(Users::hasAccess('delete','tickets',$this->getUser(),$request->getParameter('projects_id')))
    {
      $s[] = array('title'=>t::__('Delete'),'url'=>'tickets/delete?id=' . $request->getParameter('tickets_id') . '&projects_id=' . $request->getParameter('projects_id'),'confirm'=>true);
    }
    
    if(count($s)>0)
    {
      $more_actions[] = array('title'=>t::__('More Actions'),'submenu'=>$s);
    }
    
    return $more_actions; 
  }  

  public function executeNew(sfWebRequest $request)
  {        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('tickets_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',$this->tickets, $this->projects);
    }
    else
    {    
      $this->projects = null;
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('tickets_id'))), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkTicketsAccess('view',$this->tickets);
    }
    
    Users::checkAccess($this,'insert','ticketsComments',$this->getUser(),$request->getParameter('projects_id'));
  
    $this->form = new TicketsCommentsForm(null, array('tickets'=>$this->tickets));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
            
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('tickets_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',$this->tickets, $this->projects);
    }
    else
    {
      $this->projects = null;
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('tickets_id'))), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkTicketsAccess('view',$this->tickets);
    }
    
    Users::checkAccess($this,'insert','ticketsComments',$this->getUser(),$request->getParameter('projects_id'));

    $this->form = new TicketsCommentsForm(null, array('tickets'=>$this->tickets));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('tickets_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',$this->tickets, $this->projects);
    }
    else
    {
      $this->projects = null;
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('tickets_id'))), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id'))); 
      $this->checkTicketsAccess('view',$this->tickets);
    }
    
    Users::checkAccess($this,'edit','ticketsComments',$this->getUser(),$request->getParameter('projects_id'));
  
    $this->forward404Unless($tickets_comments = Doctrine_Core::getTable('TicketsComments')->find(array($request->getParameter('id'))), sprintf('Object tickets_comments does not exist (%s).', $request->getParameter('id')));
    $this->form = new TicketsCommentsForm($tickets_comments, array('tickets'=>$this->tickets));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
            
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('tickets_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',$this->tickets, $this->projects);
    }
    else
    {
      $this->projects = null;
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('tickets_id'))), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkTicketsAccess('view',$this->tickets);
    }
    
    Users::checkAccess($this,'edit','ticketsComments',$this->getUser(),$request->getParameter('projects_id'));
    
    $this->forward404Unless($tickets_comments = Doctrine_Core::getTable('TicketsComments')->find(array($request->getParameter('id'))), sprintf('Object tickets_comments does not exist (%s).', $request->getParameter('id')));
    $this->form = new TicketsCommentsForm($tickets_comments, array('tickets'=>$this->tickets));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('tickets_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',$this->tickets, $this->projects);
    }
    else
    {
      $this->forward404Unless($this->tickets = Doctrine_Core::getTable('Tickets')->find($request->getParameter('tickets_id')), sprintf('Object tasks does not exist (%s).', $request->getParameter('tickets_id')));
      $this->checkTicketsAccess('view',$this->tickets);
    }
    
    Users::checkAccess($this,'delete','ticketsComments',$this->getUser(),$request->getParameter('projects_id'));

    $this->forward404Unless($tickets_comments = Doctrine_Core::getTable('TicketsComments')->find(array($request->getParameter('id'))), sprintf('Object tickets_comments does not exist (%s).', $request->getParameter('id')));
    
    
    $tickets_comments->delete();
    Attachments::resetAttachments();

    $this->redirect('ticketsComments/index?tickets_id=' . $request->getParameter('tickets_id') . ($request->getParameter('projects_id')>0?'&projects_id=' . $request->getParameter('projects_id'):''));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if($form->getObject()->isNew())
      {
        $tickets = Doctrine_Core::getTable('Tickets')->find($request->getParameter('tickets_id'));
        if($form->getValue('tickets_status_id')>0)
        { 
          $tickets->setTicketsStatusId($form->getValue('tickets_status_id'));                                  
        } 
        else 
        { 
          unset($form['tickets_status_id']); 
        }
        
        if($request->getParameter('tickets_types_id')>0){ $tickets->setTicketsTypesId($request->getParameter('tickets_types_id')); }             
        if($request->getParameter('departments_id')>0){ $tickets->setDepartmentsId($request->getParameter('departments_id')); } 
                     
        
        $tickets->save();
      }
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
      
      
      $form->protectFieldsValue();
      
      $tickets_comments = $form->save();
      
      Attachments::insertAttachments($request->getFiles(),'ticketsComments',$tickets_comments->getId(),$request->getParameter('attachments_info'),$this->getUser());          
      TicketsComments::sendNotification($this,$tickets_comments,$this->getUser());
      

      $this->redirect('ticketsComments/index?tickets_id=' . $request->getParameter('tickets_id') . ($request->getParameter('projects_id')>0?'&projects_id=' . $request->getParameter('projects_id'):''));
    }
  }
  
  public function executeInfo(sfWebRequest $request) 
  {
    if(!$this->c = Doctrine_Core::getTable('TicketsComments')->find($request->getParameter('id')))
    {
      exit();
    }
    
    $this->checkProjectsAccess($this->c->getTickets()->getProjects());
    Users::checkAccess($this,'view','ticketsComments',$this->getUser(),$this->c->getTickets()->getProjects()->getId());
    
  }  
}
