<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * scheduler actions.
 *
 * @package    sf_sandbox
 * @subpackage scheduler
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class schedulerActions extends sfActions
{
  public function checkAccess($users_id,$access='')
  {
    if($users_id>0)
    {
      if(!$this->getUser()->hasCredential('allow_manage_personal_scheduler')  or $users_id!=$this->getUser()->getAttribute('id'))
      {
        $this->redirect('accessForbidden/index');
      }
    }
    else
    {
      if(($access=='manage' and !$this->getUser()->hasCredential('public_scheduler_access_full_access')) or ($access=='view' and !$this->getUser()->hasCredential('public_scheduler_access_full_access') and !$this->getUser()->hasCredential('public_scheduler_access_view_only')))
      {
        $this->redirect('accessForbidden/index');
      }
    }
  
  }
    
  public function executePersonal(sfWebRequest $request)
  {
    $this->checkAccess($this->getUser()->getAttribute('id'),'view');
    
    if(!$this->getUser()->hasAttribute('personal_scheduler_current_time'))
    {    
      $this->getUser()->setAttribute('personal_scheduler_current_time',time());
    }
    
    if($request->hasParameter('month'))
    {
      $this->changeMonth($request->getParameter('month'),'personal_scheduler_current_time');
      $this->redirect('scheduler/personal');
    }
    
    app::setPageTitle('Personal Scheduler',$this->getResponse());
    
  }
  
  protected function changeMonth($month,$scheduler_time)
  {
    switch($month)
    {
      case 'next_month': $this->getUser()->setAttribute($scheduler_time,strtotime("+1 month",$this->getUser()->getAttribute($scheduler_time)));
        break;
      case 'prev_month': $this->getUser()->setAttribute($scheduler_time,strtotime("-1 month",$this->getUser()->getAttribute($scheduler_time)));
        break;  
      case 'current_month': $this->getUser()->setAttribute($scheduler_time,time());  
        break;   
    }
  }
  
  public function executeInfo(sfWebRequest $request)
  {
    $this->forward404Unless($this->events = Doctrine_Core::getTable('Events')->find(array($request->getParameter('id'))), sprintf('Object events does not exist (%s).', $request->getParameter('id')));
    
    $this->checkAccess($this->events->getUsersId(),'view');
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new EventsForm();
    
    $this->checkAccess($request->getParameter('users_id'),'manage');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->checkAccess($request->getParameter('users_id'),'manage');

    $this->form = new EventsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($events = Doctrine_Core::getTable('Events')->find(array($request->getParameter('id'))), sprintf('Object events does not exist (%s).', $request->getParameter('id')));
    
    $this->checkAccess($events->getUsersId(),'manage');
        
    $this->form = new EventsForm($events);
    
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($events = Doctrine_Core::getTable('Events')->find(array($request->getParameter('id'))), sprintf('Object events does not exist (%s).', $request->getParameter('id')));
    
    $this->checkAccess($events->getUsersId(),'manage');
    
    $this->form = new EventsForm($events);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {    
    $this->forward404Unless($events = Doctrine_Core::getTable('Events')->find(array($request->getParameter('id'))), sprintf('Object events does not exist (%s).', $request->getParameter('id')));
    
    $this->checkAccess($request->getParameter('users_id'),'manage');
    
    Attachments::deleteAttachmentsByBindId($events->getEventId(),'events');
    ExtraFieldsList::deleteFieldsByBindId($events->getEventId(),'events');
    
    
    $params = "?useYear=" . date('Y',app::getDateTimestamp($events->getStartDate())). "&useMonth=" . (date('n',app::getDateTimestamp($events->getStartDate()))-1);
    
    $events->delete();


    exit();
    
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {            
        $form->protectFieldsValue();
        
      $events = $form->save();
      
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$events->getEventId(),'events',$this->getUser(), $request);
            
      Attachments::insertAttachments($request->getFiles(),'events',$events->getEventId(),$request->getParameter('attachments_info'),$this->getUser());
                        
      exit();
  
    }
  }
  
  
  public function executeGetPersonalEvents()
  {
    $list = array();        
            
      foreach(events::get_events($_GET['start'],$_GET['end'],'personal',$this->getUser()->getAttribute('id')) as $events)
      {
        $start = $events['start_date'];
        $end = $events['end_date'];
        
        
        if(strstr($end,' 00:00:00'))
        {
          $end = date('Y-m-d H:i:s',strtotime('+1 day',app::getDateTimestamp($events['end_date'])));
        } 
        
        
        $bg_color = '';
         
        $list[] = array('id' => $events['event_id'],
                      'title' => addslashes($events['event_name']),
                      'description' => $events['details'],
                      'start' => str_replace(' 00:00:00','',$start),
                      'end' => str_replace(' 00:00:00','',$end),
                      'color'=> $bg_color,  
                      'editable'=>true,                                          
                      'allDay'=>(strstr($start,'00:00:00') and strstr($end,'00:00:00')),
                      'url' => app::public_url('scheduler/edit?id=' . $events['event_id'] . '&users_id=' . $this->getUser()->getAttribute('id'))                      
                      );      
      }
            
      echo json_encode($list);
      
      exit();
  }  
  
  public function executeJsonEvents(sfWebRequest $request)
  {
     $start = $request->getParameter('start');
     $end = $request->getParameter('end');
     
     $events_list = array();
     
     $added_events = array();
     for($i=$start;$i<=$end;$i+=86400)
     {
       //echo date('Y-m-d',$i) . '<br>';
       foreach(Events::getEventsListByDateQuery($i,$this->getUser()->getAttribute('id')) as $events)
       {
         if(!in_array($events->getEventId(),$added_events))
         {
           $added_events[] = $events->getEventId();
             
           $events_list[] = array('id' => $events->getEventId(),
                            			'title' => $events->getEventName(),
                            			'start' => str_replace(' ','T',$events->getStartDate()),
                            			'end' => str_replace(' ','T',$events->getEndDate()),
                                  'allDay'=>(strstr($events->getStartDate(),'00:00:00') and strstr($events->getEndDate(),'00:00:00') ? true : false),
                                  'url' => app::public_url('scheduler/edit?id=' . $events->getEventId() . '&users_id=' . $this->getUser()->getAttribute('id')))  ;
         }
       }
     }
     
     //print_r($events_list);
     
     echo json_encode($events_list);
    
    exit();
  }
  
  public function executeResize(sfWebRequest $request)
  {    
    
  
      if(strstr($_POST['end'],'T'))
      {
        $end = str_replace('T',' ',$_POST['end']);        
      }
      else
      {
        $end = date('Y-m-d',strtotime('-1 day',app::getDateTimestamp($_POST['end'])));  
      }
                  
      $events = Doctrine_Core::getTable('Events')->find($_POST['id']);
      $events->setEndDate($end);
      $events->save();
                  
      
    exit();
  }
  
  public function executeDrop(sfWebRequest $request)
  {
    if(isset($_POST['end']))
    {
      if(strstr($_POST['end'],'T'))
      {
        $end = str_replace('T',' ',$_POST['end']);
      }
      else
      {
        $end = date('Y-m-d',strtotime('-1 day',app::getDateTimestamp($_POST['end'])));        
      }
                              
      $events = Doctrine_Core::getTable('Events')->find($_POST['id']);
      $events->setStartDate($_POST['start']);
      $events->setEndDate($end);
      $events->save();
    }
    else
    {
      $events = Doctrine_Core::getTable('Events')->find($_POST['id']);
      $events->setStartDate($_POST['start']);
      $events->setEndDate($_POST['start']);
      $events->save();      
    } 
    
    exit(); 
  }
}
                                       
