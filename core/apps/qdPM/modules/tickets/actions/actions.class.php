<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * tickets actions.
 *
 * @package    sf_sandbox
 * @subpackage tickets
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ticketsActions extends sfActions
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
    else
    {
      Users::checkAccess($this,$access,'tickets',$this->getUser());
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
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',false,$this->projects);
    }
    else
    {
      $this->checkTicketsAccess('view');
    }
                            
    if(!$this->getUser()->hasAttribute('tickets_filter' . $this->get_pid($request)))
    {
      $this->getUser()->setAttribute('tickets_filter' . $this->get_pid($request), Tickets::getDefaultFilter($request,$this->getUser()));
    }
                     
    $this->filter_by = $this->getUser()->getAttribute('tickets_filter' . $this->get_pid($request));
        
    if($fb = $request->getParameter('filter_by'))
    {
      $this->filter_by[key($fb)]=current($fb);
      $this->getUser()->setAttribute('tickets_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('tickets/index' . $this->add_pid($request));
    }
    
    if($request->hasParameter('remove_filter'))
    {
      unset($this->filter_by[$request->getParameter('remove_filter')]);    
      $this->getUser()->setAttribute('tickets_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('tickets/index' . $this->add_pid($request));
    }
     
    if($request->hasParameter('user_filter'))
    {
      $this->filter_by = Tickets::useTicketsFilter($request,$this->getUser());
      $this->getUser()->setAttribute('tickets_filter' . $this->get_pid($request), $this->filter_by);
      
      $this->redirect('tickets/index' . $this->add_pid($request));
    }
        
    if($request->hasParameter('delete_user_filter'))
    {
      app::deleteUserFilter($request->getParameter('delete_user_filter'),'TicketsReports',$this->getUser());
    
      $this->getUser()->setFlash('userNotices', t::__('Filter Deleted'));
      
      $this->redirect('tickets/index' . $this->add_pid($request));
    }
    
    if($request->hasParameter('edit_user_filter'))
    {
      $this->setTemplate('editFilter','app');
    }
    
    if($request->hasParameter('sort_filters'))
    {
      $this->setTemplate('sortFilters','app');
    }
    
    if($set_order = $request->getParameter('set_order'))
    {      
      $this->getUser()->setAttribute('tickets_listing_order' . $this->get_pid($request),$set_order);
      
      $this->redirect('tickets/index' . $this->add_pid($request));
    }
    
    app::setPageTitle('Tickets',$this->getResponse());
  }

  public function executeNew(sfWebRequest $request)
  {
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($projects);
      $this->checkTicketsAccess('insert',false,$projects);
      
      $this->form = new TicketsForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));
    }
    else
    {
      $this->checkTicketsAccess('insert');
    }
    
    $this->choices = app::getProjectChoicesByUser($this->getUser(),true,'tickets',true);
    
    if(count($this->choices)==2)
    {
      unset($this->choices['']);
    }
  }
  
  public function executeNewTicket(sfWebRequest $request)
  {
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkTicketsAccess('insert',false,$projects);
    
    $this->form = new TicketsForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
    
    $this->checkProjectsAccess($projects);
    $this->checkTicketsAccess('insert',false,$projects);

    $this->form = new TicketsForm(null,array('projects'=>$projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tickets does not exist (%s).', $request->getParameter('id')));
    
      $this->checkProjectsAccess($projects);
      $this->checkTicketsAccess('edit',$tickets,$projects);
    }
    else
    {
      $projects = false;
      $this->forward404Unless($tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('id'))), sprintf('Object tickets does not exist (%s).', $request->getParameter('id')));
      $this->checkTicketsAccess('edit',$tickets);
    }
    
    $this->form = new TicketsForm($tickets,array('projects'=>$projects,'sf_user'=>$this->getUser()));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tickets does not exist (%s).', $request->getParameter('id')));
      
      $this->checkProjectsAccess($projects);
      $this->checkTicketsAccess('edit',$tickets,$projects);
    }
    else
    {
      $projects = false;
      $this->forward404Unless($tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('id'))), sprintf('Object tickets does not exist (%s).', $request->getParameter('id')));
      $this->checkTicketsAccess('edit',$tickets);
    }
    
    $this->form = new TicketsForm($tickets,array('projects'=>$projects,'sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {    
        
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      $this->forward404Unless($tickets = Doctrine_Core::getTable('Tickets')->createQuery()->addWhere('id=?',$request->getParameter('id'))->addWhere('projects_id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object tickets does not exist (%s).', $request->getParameter('id')));
      
      $this->checkProjectsAccess($projects);
      $this->checkTicketsAccess('delete',$tickets,$projects);
    }
    else
    {
      $this->forward404Unless($tickets = Doctrine_Core::getTable('Tickets')->find(array($request->getParameter('id'))), sprintf('Object tickets does not exist (%s).', $request->getParameter('id')));
      $this->checkTicketsAccess('delete',$tickets);
    }
    
    $tickets->delete();
    Attachments::resetAttachments();

    $this->redirect_to($request->getParameter('redirect_to'),$tickets->getProjectsId(), $tickets->getId(),$request);
  }
  
  protected function getSendTo($form)
  {
    $send_to = array();
    
    $allow_send = ''; 
    
    $departments = Doctrine_Core::getTable('Departments')->find($form['departments_id']->getValue());   
    
    if($form->getObject()->isNew())
    {
      $send_to['new'] = array($departments->getUsersId());
      
      $allow_send = 'new';
    }
    else
    {    
      if($form['departments_id']->getValue()!=$form->getObject()->getDepartmentsId())
      {
        $send_to['new'] = array($departments->getUsersId());
      }
      elseif($form->getObject()->getTicketsStatusId()!=$form['tickets_status_id']->getValue())
      {
        $send_to['status'] = array($departments->getUsersId());
        
        $allow_send = 'status';
      }
    }
    
    if(strlen($allow_send)>0 and sfConfig::get('app_notify_all_tickets')=='on')
    {    
      $send_to[$allow_send] = Projects::getTeamUsersByAccess($form['projects_id']->getValue(),'tickets');                                    
    }
    
    return $send_to;    
  }  

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      if($form->getObject()->isNew()){ $previeous_status = false; }else{ $previeous_status = $form->getObject()->getTicketsStatusId(); }
      if($form->getObject()->isNew()){ $previeous_departmnet = false; }else{ $previeous_departmnet = $form->getObject()->getDepartmentsId(); }
    
      $send_to = $this->getSendTo($form);
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
    
      $form->protectFieldsValue();
      
      $tickets = $form->save();
      
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$tickets->getId(),'tickets',$this->getUser(),$request);
      
      Attachments::insertAttachments($request->getFiles(),'tickets',$tickets->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      $tickets = $this->addCommentIfStatusChanged($previeous_status,$previeous_departmnet,$tickets);
      
      $this->addRelatedItems($tickets,$request);
      
      if($tickets->getUsersId()>0)      
      {        
        Tickets::sendNotification($this,$tickets,$send_to,$this->getUser(),$request->getParameter('extra_notification',array()));
      }

      $this->redirect_to($request->getParameter('redirect_to'),$tickets->getProjectsId(), $tickets->getId(),$request);
    }
  }
  
  protected function addRelatedItems($tickets,$request)
  {
    if($request->getParameter('related_tasks_id')>0)
    {
      $o = new TasksToTickets;
      $o->setTasksId($request->getParameter('related_tasks_id'))
        ->setTicketsId($tickets->getId())
        ->save();  
    }
    elseif($request->getParameter('related_discussions_id')>0)
    {
      $o = new TicketsToDiscussions;
      $o->setDiscussionsId($request->getParameter('related_discussions_id'))
        ->setTicketsId($tickets->getId())
        ->save();  
    }
    
  }
  
  protected function addCommentIfStatusChanged($previeous_status,$previeous_departmnet,$tickets)
  {  
              
    if(($previeous_status!=$tickets->getTicketsStatusId() or $previeous_departmnet!=$tickets->getDepartmentsId() ) and $previeous_status>0)
    {
      $c = new TicketsComments;
      if($previeous_status!=$tickets->getTicketsStatusId()){ $c->setTicketsStatusId($tickets->getTicketsStatusId()); }      
      $c->setTicketsId($tickets->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setUsersId($this->getUser()->getAttribute('id'));
      $c->save();
                  
      $tickets->save();
    }
     
    return $tickets;    
  }
  
  public function executeSaveFilter(sfWebRequest $request)
  {
    $this->setTemplate('saveFilter','app');
  }
  
  public function executeDoSaveFilter(sfWebRequest $request)
  {
    Tickets::saveTicketsFilter($request,$this->getUser()->getAttribute('tickets_filter' . $this->get_pid($request)),$this->getUser());
    
    $this->getUser()->setFlash('userNotices', t::__('Filter Saved'));
    $this->redirect('tickets/index' . $this->add_pid($request));
  }
  
  protected function redirect_to($redirect_to,$projects_id,$tickets_id,$request=false)
  {
    if($request)
    {
      if($request->getParameter('related_tasks_id')>0)
      {
        $this->redirect('tasksComments/index?projects_id=' . $projects_id . '&tasks_id=' . $request->getParameter('related_tasks_id'));
      }
      elseif($request->getParameter('related_discussions_id')>0)
      {
        $this->redirect('discussionsComments/index?projects_id=' . $projects_id . '&discussions_id=' . $request->getParameter('related_discussions_id'));
      }
    }
      
    switch(true)
    {
      case $redirect_to=='dashboard': $this->redirect('dashboard/index');
        break;
      case $redirect_to=='ticketsList': $this->redirect('tickets/index');
        break;  
      case $redirect_to=='ticketsComments': $this->redirect('ticketsComments/index?tickets_id=' . $tickets_id . ($projects_id>0 ? '&projects_id=' . $projects_id :''));
        break;
      case strstr($redirect_to,'ticketsReports'): $this->redirect('ticketsReports/view?id=' . str_replace('ticketsReports','',$redirect_to));
        break;
      default: $this->redirect('tickets/index' . ($projects_id>0 ?'?projects_id=' . $projects_id:''));
        break;  
    }
  }
  
  public function executeMoveTo(sfWebRequest $request)
  {  
    if($request->getParameter('projects_id')>0)
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('edit',false,$this->projects);
    }
    else
    {
      $this->checkTicketsAccess('edit');
    }
    
    if($request->getParameter('move_to')>0)
    {
      Doctrine_Query::create()
              ->update('Tickets')
              ->set('projects_id',$request->getParameter('move_to'))              
              ->whereIn('id', explode(',',$request->getParameter('selected_items')))
              ->execute();
      
      $this->redirect_to($request->getParameter('redirect_to'),$request->getParameter('move_to'),$request->getParameter('tickets_id'));
    }    
  } 
  
  
  
  
  public function executeExport(sfWebRequest $request)
  {
    /*check access*/
    if($request->hasParameter('projects_id'))
    {
      $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('projects_id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('projects_id')));
      
      $this->checkProjectsAccess($this->projects);
      $this->checkTicketsAccess('view',false,$this->projects);
    }
    else
    {
      $this->checkTicketsAccess('view');
    }
  
    $this->columns = array(
                           'Projects'       => t::__('Project Name'),
                           'id'=>t::__('Id'),                           
                           'TicketsStatus'=>t::__('Status'),
                           'TicketsTypes'=>t::__('Type'),                                                      
                           'name'=>t::__('Name'),
                           'description'=>t::__('Description'),
                           'Departments'=>t::__('Department'),                                                                                 
                           'Users'=>t::__('Created By'),
                           'created_at'=>t::__('Created At'),
                           );
                           
    $extra_fields = ExtraFieldsList::getFieldsByType('tickets',$this->getUser(),false,array('all'=>true));
    
    foreach($extra_fields as $v)
    {
      $this->columns['extra_field_' . $v['id']]=$v['name'];
    }  
    
    if(!$request->hasParameter('projects_id'))
    {
      $this->columns['Projects']=t::__('Project');
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
      
       $q = Doctrine_Core::getTable('Tickets')->createQuery('t')          
          ->leftJoin('t.TicketsStatus ts')          
          ->leftJoin('t.TicketsTypes tt')                              
          ->leftJoin('t.Departments td')
          ->leftJoin('t.Projects p')
          ->leftJoin('t.Users')                              
          ->whereIn('t.id',explode(',',$request->getParameter('selected_items')));
          
      if($request->hasParameter('projects_id'))
      {
        $q->addWhere('projects_id=?',$request->getParameter('projects_id'));
        
        if(Users::hasAccess('view_own','tickets',$this->getUser(),$request->getParameter('projects_id')))
        {                 
          $q->addWhere("t.departments_id in (" . implode(',',Departments::getDepartmentIdByUserId($this->getUser()->getAttribute('id'))). ") or t.users_id='" . $this->getUser()->getAttribute('id') . "'");
        }
      }
      else
      {
        if(Users::hasAccess('view_own','projects',$this->getUser()))
        {       
          $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',team) or p.users_id='" . $this->getUser()->getAttribute('id') . "'");
        }
        
        if(Users::hasAccess('view_own','tickets',$this->getUser()))
        {                 
          $q->addWhere("t.departments_id in (" . implode(',',Departments::getDepartmentIdByUserId($this->getUser()->getAttribute('id'))). ") or t.users_id='" . $this->getUser()->getAttribute('id') . "'");
        }
      }
                  
      if($request->hasParameter('projects_id'))
      {
        $q = app::addListingOrder($q,'tickets',$this->getUser(), (int)$request->getParameter('projects_id'));
      }
      else
      {
        $q->orderBy('LTRIM(p.name), ts.sort_order, LTRIM(ts.name), LTRIM(t.name)');
      }       
          
      $tickets = $q->fetchArray();
      
      $totals = array();
      $projects_totals = array();
      $current_project_id = 0;
          
      foreach($tickets as $t)
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

          elseif($f=='created_at')
          {            
            if(strlen($t[$f])>0)
            {
              $v = app::dateTimeFormat($t[$f]);
            }            
          }
          elseif($f=='url')
          {
            $v = app::public_url('ticketsComments/index?projects_id=' . $t['projects_id'] . '&tickets_id=' . $t['id']);
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
      $this->checkTicketsAccess('edit',false,$this->projects);
    }
    else
    {
      $this->checkTicketsAccess('edit');
    }
    
    $this->fields = array();
    
    if($request->getParameter('projects_id')>0)
    {
      $this->fields['departments_id'] = array('title'=>t::__('Department'),'choices'=>app::getItemsChoicesByTable('Departments',true));
    }
          
    $choices = app::getItemsChoicesByTable('TicketsStatus',true);
    if(count($choices)>1) $this->fields['tickets_status_id'] = array('title'=>t::__('Status'),'choices'=>$choices);
    
    $choices = app::getItemsChoicesByTable('TicketsTypes',true);
    if(count($choices)>1) $this->fields['tickets_types_id'] = array('title'=>t::__('Type'),'choices'=>$choices);
    
    
    if($request->getParameter('fields'))
    {
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
      foreach($request->getParameter('fields') as $key=>$value)
      {
        if(strlen($value)==0 and !is_array($value)) continue;
                
        if($key=='tickets_status_id' )
        {
          foreach(explode(',',$request->getParameter('selected_items')) as $pid)
          {
            if($p = Doctrine_Core::getTable('Tickets')->find($pid))
            {
              if( $p->getTicketsStatusId()!=$value)
              {                             
                $p->setTicketsStatusId($value);                                                                                        
                $p->save();
                                                   
                if($p->getDepartmentsId()>0)
                {
                  Tickets::sendNotification($this, $p,array('status'=>explode(',',$p->getDepartments()->getUsersId())),$this->getUser());
                }
                
                $c = new TicketsComments;
                $c->setTicketsStatusId($value);
                $c->setTicketsId($pid);
                $c->setCreatedAt(date('Y-m-d H:i:s'));
                $c->setUsersId($this->getUser()->getAttribute('id'));
                $c->save();
              }
            }
          }
        }
        elseif( $key=='departments_id')
        {
          foreach(explode(',',$request->getParameter('selected_items')) as $pid)
          {
            if($p = Doctrine_Core::getTable('Tickets')->find($pid))
            {
              if($p->getDepartmentsId()!=$value)
              {                                    
                $p->setDepartmentsId($value);                                                                      
                $p->save();
                
                if($p->getDepartmentsId()>0)
                {
                  Tickets::sendNotification($this, $p,array('new'=>explode(',',$p->getDepartments()->getUsersId())),$this->getUser());
                }                                
              }
            }
          }
        }
        else
        {
      
          Doctrine_Query::create()
            ->update('Tickets')
            ->set($key,$value)                  
            ->whereIn('id', explode(',',$request->getParameter('selected_items')))
            ->execute();
        }
        
      }
      
      $this->redirect_to($request->getParameter('redirect_to'),$request->getParameter('projects_id'),$request->getParameter('tickets_id'));
    }        
  } 
  
}
