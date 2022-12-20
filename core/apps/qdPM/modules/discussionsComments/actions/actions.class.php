<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * discussionsComments actions.
 *
 * @package    sf_sandbox
 * @subpackage discussionsComments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class discussionsCommentsActions extends sfActions
{
  protected function checkProjectsAccess($projects)
  {    
    Projects::checkViewOwnAccess($this,$this->getUser(),$projects);    
  }
  
  protected function checkDiscussionsAccess($access,$discussions=false,$projects=false)
  {
    if($projects)
    {
      Users::checkAccess($this,$access,'discussions',$this->getUser(),$projects->getId());
      if($discussions)
      {
        Discussions::checkViewOwnAccess($this,$this->getUser(),$discussions,$projects);
      }
    }
  }
  
  protected function checkViewOwnAccess($comments,$projects)
  {   
    if(Users::hasAccess('view_own','discussionsComments',$this->getUser(),$projects->getId()))
    {
      if($comments->getUsersId()!=$this->getUser()->getAttribute('id'))
      {
        $this->redirect('accessForbidden/index');
      }
    }
  }
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('discussions_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('discussions_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkDiscussionsAccess('view',$this->discussions, $this->projects);
    
    if(!$this->getUser()->hasAttribute('discussions_filter' . $request->getParameter('projects_id')))
    {
      $this->getUser()->setAttribute('discussions_filter' . $request->getParameter('projects_id'), Discussions::getDefaultFilter($request,$this->getUser()));
    }
  
    $this->discussions_comments = Doctrine_Core::getTable('DiscussionsComments')
      ->createQuery('dc')      
      ->leftJoin('dc.Users u')
      ->addWhere('discussions_id=?',$request->getParameter('discussions_id'))
      
      ->orderBy('created_at desc')
      ->fetchArray();
      
      $this->more_actions = $this->getMoreActions($request);
      
      app::setPageTitle(t::__('Discussion') . ' | ' .  $this->discussions->getName(),$this->getResponse());
  }
  
  
  protected function getMoreActions(sfWebRequest $request)
  {
    $more_actions = array();    
    $s = array();
    
    
    if(Users::hasAccess('insert','tasks',$this->getUser(),$request->getParameter('projects_id')))
    {
      $s[] = array('title'=>t::__('Related Tasks'),
                   'submenu'=>array( array('title'=>t::__('Add Task'),'url'=>'tasks/new?related_discussions_id=' . $request->getParameter('discussions_id') . '&projects_id=' . $request->getParameter('projects_id'),'modalbox'=>true)
                                    
                                    ));
    }
    
       
    if(Users::hasAccess('edit','discussions',$this->getUser(),$request->getParameter('projects_id')))
    {      
      $s[] = array('title'=>t::__('Move To'),'url'=>'discussions/moveTo?discussions_id=' . $request->getParameter('discussions_id') . '&projects_id=' . $request->getParameter('projects_id') . '&redirect_to=discussionsComments','modalbox'=>true);
    }
    
    if(Users::hasAccess('delete','discussions',$this->getUser(),$request->getParameter('projects_id')))
    {
      $s[] = array('title'=>t::__('Delete'),'url'=>'discussions/delete?id=' . $request->getParameter('discussions_id') . '&projects_id=' . $request->getParameter('projects_id'),'confirm'=>true);
    }
    
    if(count($s)>0)
    {
      $more_actions[] = array('title'=>t::__('More Actions'),'submenu'=>$s);
    }
    
    return $more_actions; 
  }  

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('discussions_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('discussions_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkDiscussionsAccess('view',$this->discussions, $this->projects);
    Users::checkAccess($this,'insert','discussionsComments',$this->getUser(),$this->projects->getId());
    
    $this->form = new DiscussionsCommentsForm(null, array('discussions'=>$this->discussions));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('discussions_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('discussions_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkDiscussionsAccess('view',$this->discussions, $this->projects);
    Users::checkAccess($this,'insert','discussionsComments',$this->getUser(),$this->projects->getId());

    $this->form = new DiscussionsCommentsForm(null, array('discussions'=>$this->discussions));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($discussions_comments = Doctrine_Core::getTable('DiscussionsComments')->find(array($request->getParameter('id'))), sprintf('Object discussions_comments does not exist (%s).', $request->getParameter('id')));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('discussions_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('discussions_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkDiscussionsAccess('view',$this->discussions, $this->projects);
    Users::checkAccess($this,'edit','discussionsComments',$this->getUser(),$this->projects->getId());
    $this->checkViewOwnAccess($discussions_comments,$this->projects);
      
    $this->form = new DiscussionsCommentsForm($discussions_comments, array('discussions'=>$this->discussions));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($discussions_comments = Doctrine_Core::getTable('DiscussionsComments')->find(array($request->getParameter('id'))), sprintf('Object discussions_comments does not exist (%s).', $request->getParameter('id')));
        
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('discussions_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('discussions_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkDiscussionsAccess('view',$this->discussions, $this->projects);
    Users::checkAccess($this,'edit','discussionsComments',$this->getUser(),$this->projects->getId());
    $this->checkViewOwnAccess($discussions_comments,$this->projects);
    
    $this->form = new DiscussionsCommentsForm($discussions_comments, array('discussions'=>$this->discussions));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($discussions_comments = Doctrine_Core::getTable('DiscussionsComments')->find(array($request->getParameter('id'))), sprintf('Object discussions_comments does not exist (%s).', $request->getParameter('id')));
    
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($this->discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('discussions_id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('discussions_id')));
    $this->checkProjectsAccess($this->projects);
    $this->checkDiscussionsAccess('view',$this->discussions, $this->projects);
    Users::checkAccess($this,'delete','discussionsComments',$this->getUser(),$this->projects->getId());
    $this->checkViewOwnAccess($discussions_comments,$this->projects);
                            
    $discussions_comments->delete();
    Attachments::resetAttachments();

    $this->redirect('discussionsComments/index?projects_id=' . $request->getParameter('projects_id') . '&discussions_id=' . $request->getParameter('discussions_id'));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if($form->getObject()->isNew())
      {
        $discussions = Doctrine_Core::getTable('Discussions')->find($request->getParameter('discussions_id'));            
        if($form->getValue('discussions_status_id')>0){ $discussions->setDiscussionsStatusId($form->getValue('discussions_status_id')); } else { unset($form['discussions_status_id']); }      
        $discussions->save();
      }
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
      
      $form->protectFieldsValue();
    
      $discussions_comments = $form->save();
      
      Attachments::insertAttachments($request->getFiles(),'discussionsComments',$discussions_comments->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      DiscussionsComments::sendNotification($this,$discussions_comments,$this->getUser());

      $this->redirect('discussionsComments/index?projects_id=' . $request->getParameter('projects_id') . '&discussions_id=' . $request->getParameter('discussions_id'));
    }
  }
  
  public function executeInfo(sfWebRequest $request) 
  {
    if(!$this->c = Doctrine_Core::getTable('DiscussionsComments')->find($request->getParameter('id')))
    {
      exit();
    }
    
    $this->checkProjectsAccess($this->c->getDiscussions()->getProjects());
    Users::checkAccess($this,'view','discussionsComments',$this->getUser(),$this->c->getDiscussions()->getProjects()->getId());
    
  }
}
