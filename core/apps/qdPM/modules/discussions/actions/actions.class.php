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
 * discussions actions.
 *
 * @package    sf_sandbox
 * @subpackage discussions
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class discussionsActions extends sfActions
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
    else
    {
      Users::checkAccess($this,$access,'discussions',$this->getUser());
    }
  }

  protected function add_pid($request,$sep='?')
  {
    if((int)$request->getParameter('projects_id')>0)
    {
      return $sep . 'projects_id=' . $request->getParameter('projects_id');
    }
    else
    {
      return '';
    }
  }
  
  protected function get_pid($request)
  {
    return ((int)$request->getParameter('projects_id')>0 ? $request->getParameter('projects_id') : '');
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkDiscussionsAccess('view',false,$this->projects);
    }
    else
    {
      $this->checkDiscussionsAccess('view');
    }
                            
    if(!$this->getUser()->hasAttribute('discussions_filter' . $this->get_pid($request)))
    {
      $this->getUser()->setAttribute('discussions_filter' . $this->get_pid($request), Discussions::getDefaultFilter($request,$this->getUser()));
    }
                     
    $this->filter_by = $this->getUser()->getAttribute('discussions_filter' . $this->get_pid($request));
        
    if($fb = $request->getParameter('filter_by'))
    {
      $this->filter_by[key($fb)]=current($fb);
      $this->getUser()->setAttribute('discussions_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('discussions/index' . $this->add_pid($request));
    }
    
    if($request->hasParameter('remove_filter'))
    {
      unset($this->filter_by[$request->getParameter('remove_filter')]);    
      $this->getUser()->setAttribute('discussions_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('discussions/index' . $this->add_pid($request));
    }
     
    
    if($set_order = $request->getParameter('set_order'))
    {      
      $this->getUser()->setAttribute('discussions_listing_order' . $this->get_pid($request),$set_order);
      
      $this->redirect('discussions/index' . $this->add_pid($request));
    }
    
    app::setPageTitle('Discussions',$this->getResponse());
  }
  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($projects);
      $this->checkDiscussionsAccess('insert',false,$projects);
      
      $this->form = new DiscussionsForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));
    }
    else
    {
      $this->checkDiscussionsAccess('insert');
    }
    
    $this->choices = app::getProjectChoicesByUser($this->getUser(),true,'discussions',true);
    
    if(count($this->choices)==2)
    {
      unset($this->choices['']);
    }
  }
  
  public function executeNewDiscussion(sfWebRequest $request)
  {
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkDiscussionsAccess('insert',false,$projects);
    
    $this->form = new DiscussionsForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));

    $this->checkProjectsAccess($projects);
    $this->checkDiscussionsAccess('insert',false,$projects);
    
    $this->form = new DiscussionsForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkDiscussionsAccess('edit',$discussions, $projects);
    
    $this->form = new DiscussionsForm($discussions,array('projects'=>$projects,'sf_user'=>$this->getUser()));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkDiscussionsAccess('edit',$discussions, $projects);
    
    $this->form = new DiscussionsForm($discussions,array('projects'=>$projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {    

    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    $this->forward404Unless($discussions = Doctrine_Core::getTable('Discussions')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object discussions does not exist (%s).', $request->getParameter('id')));
    
    
    $this->checkProjectsAccess($projects);
    $this->checkDiscussionsAccess('delete',$discussions,$projects);
        
    $discussions->delete();
    Attachments::resetAttachments();

    $this->redirect_to($request->getParameter('redirect_to'),$discussions->getProjectsId(), $discussions->getId());
  }
  
  protected function getSendTo($form)
  {
    $send_to = array(); 
    
    $allow_send = '';   
    
    if($form->getObject()->isNew())
    {
      $send_to['new'] = $form['assigned_to']->getValue();;
      
      $allow_send = 'new';
    }
    else
    {    
      if(is_array($form['assigned_to']->getValue()))  
      if(count($new_users = array_diff($form['assigned_to']->getValue(),explode(',',$form->getObject()->getAssignedTo())))>0)
      {
        $send_to['new_assigned'] = $new_users;
      }
    
      if($form->getObject()->getDiscussionsStatusId()!=$form['discussions_status_id']->getValue())
      {
        if(isset($send_to['new_assigned']))
        {
          $send_to['status'] = array_diff(explode(',',$form->getObject()->getAssignedTo()),$new_users);
        }
        else
        {
          $send_to['status'] = $form['assigned_to']->getValue();                    
        }  
        
        $allow_send = 'status';
      }
    }
    
    if(strlen($allow_send)>0 and sfConfig::get('app_notify_all_discussions')=='on')
    {
      $send_to[$allow_send] = Projects::getTeamUsersByAccess($form['projects_id']->getValue(),'discussions');                  
    }
    
    return $send_to;    
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if(is_array($form['assigned_to']->getValue()))
        $form->setFieldValue('assigned_to',implode(',',$form['assigned_to']->getValue()));
      
      $send_to = $this->getSendTo($form);
      
      if($form->getObject()->isNew()){ $previeous_status = false; }else{ $previeous_status = $form->getObject()->getDiscussionsStatusId(); }
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
      
      $form->protectFieldsValue();
      
      $discussions = $form->save();
      
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$discussions->getId(),'discussions',$this->getUser(),$request);
      
      Attachments::insertAttachments($request->getFiles(),'discussions',$discussions->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      $discussions = $this->addCommentIfStatusChanged($previeous_status,$discussions);
      
      $discussions = $this->checkIfAssignedToChanged($send_to,$discussions);
      
      $this->addRelatedItems($discussions,$request);
      
      Discussions::sendNotification($this,$discussions,$send_to,$this->getUser());

      $this->redirect_to($request->getParameter('redirect_to'),$discussions->getProjectsId(), $discussions->getId(),$request);
    }
  }
  
  protected function checkIfAssignedToChanged($send_to,$discussions)
  {
    if(isset($send_to['new_assigned']))
    {                  
      $c = new DiscussionsComments;
      $c->setDescription(t::__('Assigned To') . ': ' .  Users::getNameById(implode(',',$send_to['new_assigned']),', '));
      $c->setDiscussionsId($discussions->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setUsersId($this->getUser()->getAttribute('id'));
      $c->save();
            
      $discussions->save();
    }
    
    return $discussions;    
  
  }
  
  protected function addRelatedItems($discussions,$request)
  {
    if($request->getParameter('related_tickets_id')>0)
    {
      $o = new TicketsToDiscussions;
      $o->setDiscussionsId($discussions->getId())
        ->setTicketsId($request->getParameter('related_tickets_id'))
        ->save();  
    }
    elseif($request->getParameter('related_tasks_id')>0)
    {
      $o = new TasksToDiscussions;
      $o->setTasksId($request->getParameter('related_tasks_id'))
        ->setDiscussionsId($discussions->getId())
        ->save();  
    }  
  }  
  
  protected function redirect_to($redirect_to,$projects_id,$discussions_id,$request=false)
  {
    if($request)
    {
      if($request->getParameter('related_tickets_id')>0)
      {
        $this->redirect('ticketsComments/index?projects_id=' . $projects_id . '&tickets_id=' . $request->getParameter('related_tickets_id'));
      }
      elseif($request->getParameter('related_tasks_id')>0)
      {
        $this->redirect('tasksComments/index?projects_id=' . $projects_id . '&tasks_id=' . $request->getParameter('related_tasks_id'));
      }
    }
    
    switch(true)
    {
      case $redirect_to=='dashboard': $this->redirect('dashboard/index');
        break;
      case $redirect_to=='discussionsList': $this->redirect('discussions/index');
        break;  
      case $redirect_to=='discussionsComments': $this->redirect('discussionsComments/index?projects_id=' . $projects_id . '&discussions_id=' . $discussions_id);
        break;
      case strstr($redirect_to,'discussionsReports'): $this->redirect('discussionsReports/view?id=' . str_replace('discussionsReports','',$redirect_to));
        break;
      default: $this->redirect('discussions/index' . ($projects_id>0 ? '?projects_id=' . $projects_id:''));
        break;  
    }
  }
  
  protected function addCommentIfStatusChanged($previeous_status,$discussions)
  {    
    if($previeous_status!=$discussions->getDiscussionsStatusId() and $previeous_status>0)
    {
      $c = new DiscussionsComments;
      $c->setDiscussionsStatusId($discussions->getDiscussionsStatusId());
      $c->setDiscussionsId($discussions->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setUsersId($this->getUser()->getAttribute('id'));
      $c->save();
            
      $discussions->save();
    }
     
    return $discussions;    
  }  
  
  public function executeSaveFilter(sfWebRequest $request)
  {
    $this->setTemplate('saveFilter','app');
  }
  
  public function executeDoSaveFilter(sfWebRequest $request)
  {
    Discussions::saveDiscussionsFilter($request,$this->getUser()->getAttribute('discussions_filter' . $this->get_pid($request)),$this->getUser());
    
    $this->getUser()->setFlash('userNotices', t::__('Filter Saved'));
    $this->redirect('discussions/index' . $this->add_pid($request));
  }
  
  public function executeExport(sfWebRequest $request)
  {
    /*check access*/
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkDiscussionsAccess('view',false,$this->projects);
    }
    else
    {
      $this->checkDiscussionsAccess('view');
    }
  
    $this->columns = array(
                           'Projects'       => t::__('Project Name'),
                           'id'=>t::__('Id'),                           
                           'DiscussionsStatus'=>t::__('Status'),                                                                                 
                           'name'=>t::__('Name'),
                           'description'=>t::__('Description'),
                           'assigned_to'=>t::__('Assigend To'),                                                                                 
                           'Users'=>t::__('Created By'),
                           
                           );
                           
    $extra_fields = ExtraFieldsList::getFieldsByType('discussions',$this->getUser(),false,array('all'=>true));
    
    foreach($extra_fields as $v)
    {
      $this->columns['extra_field_' . $v['id']]=$v['name'];
    }                           
    
    $this->columns['Projects']=t::__('Project Name');
    $this->columns['url']=t::__('Url');
    
    if($fields = $request->getParameter('fields'))
    {
      $separator = "\t";
      $format = $request->getParameter('format','.csv');
      $filename = $request->getParameter('filename','tasks');
			
			header("Content-type: Application/octet-stream");      
			header("Content-disposition: attachment; filename=" . $filename . "." . $format);
			header("Pragma: no-cache");
			header("Expires: 0");
    
      $content = '';
      foreach($fields as $f)
      {
        $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$this->columns[$f]) . $separator;
      }
      $content .= "\n";
      
      if($format=='csv')
      {
        echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
      }
      else
      {
        echo $content;
      }
    
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
      $q = Doctrine_Core::getTable('Discussions')->createQuery('d')          
          ->leftJoin('d.DiscussionsStatus ds')                                                 
          ->leftJoin('d.Projects p')
          ->leftJoin('d.Users')                              
          ->whereIn('d.id',explode(',',$request->getParameter('selected_items')));
          
      if($request->hasParameter('projects_id'))
      {
        $q->addWhere('projects_id=?',$request->getParameter('projects_id'));
        
        if(Users::hasAccess('view_own','discussions',$this->getUser(),$request->getParameter('projects_id')))
        {                 
          $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',d.assigned_to) or d.users_id='" . $this->getUser()->getAttribute('id') . "'");
        }
      }
      else
      {
        if(Users::hasAccess('view_own','projects',$this->getUser()))
        {       
          $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
        }
        
        if(Users::hasAccess('view_own','discussions',$this->getUser()))
        {                 
          $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',d.assigned_to) or d.users_id='" . $this->getUser()->getAttribute('id') . "'");
        }
        
      }
                  
      if($request->hasParameter('projects_id'))
      {
        $q = app::addListingOrder($q,'discussions',$this->getUser(), (int)$request->getParameter('projects_id'));
      }
      else
      {
        $q->orderBy('LTRIM(p.name), ds.sort_order, LTRIM(ds.name), LTRIM(d.name)');
      }    
          
      $discussions = $q->execute();
      
      $totals = array();
      $projects_totals = array();
      $current_project_id = 0;
          
      foreach($discussions as $t)
      {
        $ex_values = ExtraFieldsList::getValuesList($extra_fields,$t['id']);
        
        $content = '';
        
        //
        if($current_project_id==0) $current_project_id = $t['projects_id'];
         
        if($current_project_id!=$t['projects_id'])
        {
          
           //adding totals
          if(isset($projects_totals[$current_project_id]))
          {           
            foreach($fields as $f)
            {
              $v = '';                        
              if(strstr($f,'extra_field_'))
              {          
                if(isset($projects_totals[$current_project_id][str_replace('extra_field_','',$f)])) $v = $projects_totals[$current_project_id][str_replace('extra_field_','',$f)];                        
              }
                      
              $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
            }
                      
            $content .= "\n\n";
          }          
          
          $current_project_id=$t['projects_id'];
        }
        
        
        foreach($fields as $f)
        {
          $v = '';
          
          if(in_array($f,array('id','name','description')))
          {
            $v = str_replace(array("\n","\r","\n\r"),'',$t[$f]);
          }
          elseif(strstr($f,'extra_field_'))
          {
            if($ex = Doctrine_Core::getTable('ExtraFields')->find(str_replace('extra_field_','',$f)))
            {
              $v = ExtraFieldsList::renderFieldValueByType($ex,$ex_values,array(),true);
              
              if(in_array($ex->getType(),array('number','formula')))
              {
                if(!isset($totals[$ex->getId()])) $totals[$ex->getId()] = 0;
                if(!isset($projects_totals[$t['projects_id']][$ex->getId()])) $projects_totals[$t['projects_id']][$ex->getId()] = 0;
                
                $totals[$ex->getId()]+= $v;
                $projects_totals[$t['projects_id']][$ex->getId()]+=$v;              
              }
              
              $v = str_replace('<br>',', ',$v);
            }
          }
          elseif($f=='assigned_to')
          { 
            $v = Users::getNameById($t[$f],', ');            
          }          
          elseif($f=='created_at')
          {            
            if(strlen($t[$f])>0)
            {
              $v = app::dateTimeFormat($t[$f]);
            }            
          }
          elseif($f=='url')
          {
            $v = app::public_url('discussionsComments/index?projects_id=' . $t['projects_id'] . '&discussions_id=' . $t['id']);
          }
          else
          {
            $v=app::getArrayName($t,$f);
          }
          
          $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
        }
        
        $content .= "\n";
      
        if($format=='csv')
        {
          echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
        }
        else
        {
          echo $content;
        }        
      }
      
      $content = '';
      
      //adding totals
      if(isset($projects_totals[$current_project_id]) and !$request->hasParameter('projects_id'))
      {
        foreach($fields as $f)
        {
          $v = '';          
          
          if(strstr($f,'extra_field_'))
          {          
            if(isset($projects_totals[$current_project_id][str_replace('extra_field_','',$f)])) $v = $projects_totals[$current_project_id][str_replace('extra_field_','',$f)];            
          }
                  
          $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
        }
                  
        $content .= "\n\n";
      }
      
      foreach($fields as $f)
      {
        $v = '';          
        
        if(strstr($f,'extra_field_'))
        {          
          if(isset($totals[str_replace('extra_field_','',$f)])) $v = $totals[str_replace('extra_field_','',$f)];          
        }
                
        $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
      }
      
      $content .= "\n";
      
      if($format=='csv')
      {
        echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
      }
      else
      {
        echo $content;
      }      
            
      exit(); 
    }
  }
  
  public function executeMultipleEdit(sfWebRequest $request)
  {  
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkDiscussionsAccess('edit',false,$this->projects);
    }
    else
    {
      $this->checkDiscussionsAccess('edit');
    }
    
    $this->fields = array();
        
    $choices = app::getItemsChoicesByTable('DiscussionsStatus',true);
    if(count($choices)>1) $this->fields['discussions_status_id'] = array('title'=>t::__('Status'),'choices'=>$choices);

    
    if($request->getParameter('fields'))
    {
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
      foreach($request->getParameter('fields') as $key=>$value)
      {
        if(strlen($value)==0 and !is_array($value)) continue;
        
        
        if($key=='discussions_status_id')
        {
          foreach(explode(',',$request->getParameter('selected_items')) as $pid)
          {
            if($p = Doctrine_Core::getTable('Discussions')->find($pid))
            {
              if($p->getDiscussionsStatusId()!=$value)
              {
                $p->setDiscussionsStatusId($value);                
                $p->save();
                
                if(strlen($p->getAssignedTo())>0)
                {
                  Discussions::sendNotification($this, $p,array('status'=>explode(',',$p->getAssignedTo())),$this->getUser());
                }
                
                $c = new DiscussionsComments;
                $c->setDiscussionsStatusId($value);
                $c->setDiscussionsId($pid);
                $c->setCreatedAt(date('Y-m-d H:i:s'));
                $c->setUsersId($this->getUser()->getAttribute('id'));
                $c->save();
              }
            }
          }
        }
        else
        {        
          Doctrine_Query::create()
            ->update('Discussions')
            ->set($key,$value)                  
            ->whereIn('id', explode(',',$request->getParameter('selected_items')))
            ->execute();
                          
        }        
      }
      
      $this->redirect_to($request->getParameter('redirect_to'),$request->getParameter('projects_id'),$request->getParameter('discussions_id'));
    }        
  } 
  
  public function executeMoveTo(sfWebRequest $request)
  {  
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkDiscussionsAccess('edit',false,$this->projects);
    }
    else
    {
      $this->checkDiscussionsAccess('edit');
    }
    
    if($request->getParameter('move_to')>0)
    {
      Doctrine_Query::create()
              ->update('Discussions')
              ->set('projects_id',$request->getParameter('move_to'))              
              ->whereIn('id', explode(',',$request->getParameter('selected_items')))
              ->execute();
      
      $this->redirect_to($request->getParameter('redirect_to'),$request->getParameter('move_to'),$request->getParameter('discussions_id'));
    }    
  } 
      
}
