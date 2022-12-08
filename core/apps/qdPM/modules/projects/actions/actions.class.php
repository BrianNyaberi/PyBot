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
 * projects actions.
 *
 * @package    sf_sandbox
 * @subpackage projects
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectsActions extends sfActions
{
  public function executeOpen(sfWebRequest $request)
  {
    if($ug = Doctrine_Core::getTable('UsersGroups')->find($this->getUser()->getAttribute('users_group_id')))
    {
      switch(true)
      {
        case strlen($ug->getAllowManageTasks())>0:
            $this->redirect('tasks/index?projects_id=' . $request->getParameter('projects_id'));
          break;
        case strlen($ug->getAllowManageTickets())>0:
            $this->redirect('tickets/index?projects_id=' . $request->getParameter('projects_id'));
          break;
          
        case strlen($ug->getAllowManageDiscussions())>0:
            $this->redirect('discussions/index?projects_id=' . $request->getParameter('projects_id'));
          break;
        default:
            $this->redirect('projectsComments/index?projects_id=' . $request->getParameter('projects_id'));
          break;
      }
    }
    else
    {
      $this->redirect('projectsComments/index?projects_id=' . $request->getParameter('projects_id'));
    }
  }
  
  
  protected function checkProjectsAccess($access,$projects=false)
  {
    if($projects)
    {
      Users::checkAccess($this,$access,'projects',$this->getUser(),$projects->getId());
      Projects::checkViewOwnAccess($this,$this->getUser(),$projects);
    }
    else
    {
      Users::checkAccess($this,$access,'projects',$this->getUser());
    }
  }
  
  public function executeIndex(sfWebRequest $request)
  {          
    $this->checkProjectsAccess('view');
              
    if(!$this->getUser()->hasAttribute('projects_filter'))
    {
      $this->getUser()->setAttribute('projects_filter', Projects::getDefaultFilter($this->getUser()));
    }
                     
    $this->filter_by = $this->getUser()->getAttribute('projects_filter');
        
    if($fb = $request->getParameter('filter_by'))
    {
      $this->filter_by[key($fb)]=current($fb);
      $this->getUser()->setAttribute('projects_filter', $this->filter_by);
      
      $this->redirect('projects/index');
    }
    
    if($request->hasParameter('remove_filter'))
    {
      unset($this->filter_by[$request->getParameter('remove_filter')]);    
      $this->getUser()->setAttribute('projects_filter', $this->filter_by);
      
      $this->redirect('projects/index');
    }
     
    if($request->hasParameter('user_filter'))
    {
      $this->filter_by = Projects::useProjectsFilter($request->getParameter('user_filter'),$this->getUser());
      $this->getUser()->setAttribute('projects_filter', $this->filter_by);
      
      $this->redirect('projects/index');
    }

    
    if($set_order = $request->getParameter('set_order'))
    {
      $this->getUser()->setAttribute('projects_listing_order',$set_order);
      
      $this->redirect('projects/index');
    }
            
    
    app::setPageTitle('Projects',$this->getResponse());   
  }
      
  public function executeInfo(sfWebRequest $request)
  {            
    $this->forward404Unless($this->projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $this->checkProjectsAccess('view',$this->projects);        
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->checkProjectsAccess('insert');    
    
    $this->form = new ProjectsForm(null, array('sf_user'=>$this->getUser()));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->checkProjectsAccess('insert');
    
    $this->forward404Unless($request->isMethod(sfRequest::POST));
        
    $this->form = new ProjectsForm(null, array('sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {        
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $this->checkProjectsAccess('edit',$projects);
        
    $this->form = new ProjectsForm($projects, array('sf_user'=>$this->getUser()));
  }

  public function executeUpdate(sfWebRequest $request)
  {        
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->createQuery()->addWhere('id=?',$request->getParameter('id'))->fetchOne(), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $this->checkProjectsAccess('edit',$projects);
    
    $this->form = new ProjectsForm($projects, array('sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {        
    $request->checkCSRFProtection();

    $this->forward404Unless($projects = Doctrine_Core::getTable('Projects')->find(array($request->getParameter('id'))), sprintf('Object projects does not exist (%s).', $request->getParameter('id')));
    $this->checkProjectsAccess('delete',$projects);
          
    $projects->delete();
    Attachments::resetAttachments();

    $this->redirect('projects/index');
  }
    
  protected function getSendTo($form)
  {
    $send_to = array();    
    
    if($form->getObject()->isNew())
    {
      $send_to['new'] = $form['team']->getValue();;
    }
    else              
    { 
      if(count($form['team']->getValue())>0) 
      if(count($new_users = array_diff($form['team']->getValue(),explode(',',$form->getObject()->getTeam())))>0)
      {
        $send_to['new_assigned'] = $new_users;
      }
    
      if($form->getObject()->getProjectsStatusId()!=$form['projects_status_id']->getValue())
      {
        if(isset($send_to['new_assigned']))
        {
          $send_to['status'] = array_diff(explode(',',$form->getObject()->getTeam()),$new_users);
        }
        else
        {
          $send_to['status'] = $form['team']->getValue();
        }  
      }
    }
    
    return $send_to;  
  
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {    
                                        
      $form->setFieldValue('team',$form['team']->getValue());
                          
      $send_to = $this->getSendTo($form);
      
      if($form->getObject()->isNew()){ $previeous_status = false; }else{ $previeous_status = $form->getObject()->getProjectsStatusId(); }
      
      if($form->getObject()->isNew()){ $form->setFieldValue('created_at',date('Y-m-d H:i:s')); }
      
      $form->protectFieldsValue();
      
      $projects = $form->save();
                
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$projects->getId(),'projects',$this->getUser(),$request);
      
      Attachments::insertAttachments($request->getFiles(),'projects',$projects->getId(),$request->getParameter('attachments_info'),$this->getUser());
      
      $projects = $this->addCommentIfStatusChanged($previeous_status,$projects);
      
      $projects = $this->checkIfAssignedToChanged($send_to,$projects);
      
      Projects::sendNotification($this,$projects,$send_to,$this->getUser());
      
      
      $this->redirect_to($request->getParameter('redirect_to'),$projects->getId());      
    }
  }
  
  protected function checkIfAssignedToChanged($send_to,$projects)
  {
     
    if(isset($send_to['new_assigned']))
    {              
      $c = new ProjectsComments;
      $c->setDescription(t::__('Assigned To') . ': ' .  Users::getNameById(implode(',',$send_to['new_assigned']),', '));
      $c->setProjectsId($projects->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setCreatedBy($this->getUser()->getAttribute('id'));
      $c->save();
    }
    
    return $projects;    
  
  }
  
  protected function addCommentIfStatusChanged($previeous_status,$projects)
  {    
    if($previeous_status!=$projects->getProjectsStatusId() and $previeous_status>0)
    {
      $c = new ProjectsComments;      
      $c->setDescription(t::__('Status') . ': ' .  app::getNameByTableId('ProjectsStatus',$projects->getProjectsStatusId()));
      $c->setProjectsId($projects->getId());
      $c->setCreatedAt(date('Y-m-d H:i:s'));
      $c->setCreatedBy($this->getUser()->getAttribute('id'));
      $c->save();            
    }
     
    return $projects;    
  }
  
  protected function redirect_to($redirect_to,$projects_id=false)
  {
    switch(true)
    {
      case $redirect_to=='dashboard': $this->redirect('dashboard/index');
        break; 
      case $redirect_to=='projectsComments': $this->redirect('projectsComments/index?projects_id=' . $projects_id);
        break;
      case strstr($redirect_to,'projectsReports'): $this->redirect('projectsReports/view?id=' . str_replace('projectsReports','',$redirect_to));
        break;
      default:
        if($projects_id>0)
        { 
          $this->redirect('projectsComments/index?projects_id=' . $projects_id);
        }
        else
        {
          $this->redirect('projects/index');
        }
        break;  
    }
  }
  
  public function executeExport(sfWebRequest $request)
  {
    /*check access*/
    Users::checkAccess($this,'view',$this->getModuleName(),$this->getUser());
  
    $this->columns = array('id'=>t::__('Id'),                           
                           'ProjectsStatus'=>t::__('Status'),                                                      
                           'name'=>t::__('Name'),
                           'description'=>t::__('Description'),
                           'team'=>t::__('Team'),
                           'ProjectsTypes'=>t::__('Type'),                                                      
                           'Users'=>t::__('Created By'),
                           'created_at'=>t::__('Created At'),
                           );
                           
    $extra_fields = ExtraFieldsList::getFieldsByType('projects',$this->getUser(),false,array('all'=>true));
    
    foreach($extra_fields as $v)
    {
      $this->columns['extra_field_' . $v['id']]=$v['name'];
    }                           
    
    $this->columns['url']=t::__('Url');
    
    if($fields = $request->getParameter('fields'))
    {
      $separator = "\t";
      $format = $request->getParameter('format','.csv');
      $filename = $request->getParameter('filename','projects');
			
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
      
       $q= Doctrine_Core::getTable('Projects')->createQuery('p')        
          ->leftJoin('p.ProjectsStatus ps')
          ->leftJoin('p.ProjectsTypes pt')          
          ->leftJoin('p.Users')          
          ->whereIn('p.id',explode(',',$request->getParameter('selected_items')));
          
      if(Users::hasAccess('view_own','projects',$this->getUser()))
      {       
        $q->addWhere("find_in_set('" . $this->getUser()->getAttribute('id') . "',p.team) or p.created_by='" . $this->getUser()->getAttribute('id') . "'");
      }
      
      $q = app::addListingOrder($q,'projects',$this->getUser());    
          
      $projects = $q->fetchArray();
      
      $totals = array();
          
      foreach($projects as $p)
      {
        $ex_values = ExtraFieldsList::getValuesList($extra_fields,$p['id']);
        
        $content = '';
        
        foreach($fields as $f)
        {
          $v = '';
          
          if(in_array($f,array('id','name','description')))
          {            
            $v=str_replace(array("\n","\r","\n\r"),'',$p[$f]);
          }
          elseif(strstr($f,'extra_field_'))
          {
            if($ex = Doctrine_Core::getTable('ExtraFields')->find(str_replace('extra_field_','',$f)))
            {
              $v = ExtraFieldsList::renderFieldValueByType($ex,$ex_values,array(),true);
              
              if(in_array($ex->getType(),array('number','formula')))
              {
                if(!isset($totals[$ex->getId()])) $totals[$ex->getId()] = 0;
                                
                $totals[$ex->getId()]+= $v;                
              }
              
              $v = str_replace('<br>',', ',$v);
            }
          }
          elseif($f=='team')
          {  
            $v = Users::getNameById($p[$f],', ');
          }
          elseif($f=='created_at')
          {            
            if(strlen($p[$f])>0)
            {
              $v = date(app::getDateTimeFormat(),app::getDateTimestamp($p[$f]));
            }            
          }
          elseif($f=='url')
          {
            $v = app::public_url('projectsComments/index?projects_id=' . $p['id']);
          }
          else
          {            
            $v=app::getArrayName($p,$f);
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
  
    Users::checkAccess($this,'edit',$this->getModuleName(),$this->getUser());
    
    $this->fields = array();
            
    $choices = app::getItemsChoicesByTable('ProjectsStatus',true); 
    if(count($choices)>1) $this->fields['projects_status_id'] = array('title'=>t::__('Status'),'choices'=>$choices);
    
    $choices = app::getItemsChoicesByTable('ProjectsTypes',true); 
    if(count($choices)>1) $this->fields['projects_types_id'] = array('title'=>t::__('Type'),'choices'=>$choices);
    

    
    if($request->getParameter('fields'))
    {
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
      foreach($request->getParameter('fields') as $key=>$value)
      {
        if(strlen($value)==0 and !is_array($value)) continue;
              
        if($key=='projects_status_id')
        {
          foreach(explode(',',$request->getParameter('selected_items')) as $pid)
          {
            if($p = Doctrine_Core::getTable('Projects')->find($pid))
            {
              if($p->getProjectsStatusId()!=$value)
              {
                $p->setProjectsStatusId($value);                                                                  
                $p->save();
                
                
                if(strlen($p->getTeam())>0)
                {
                  Projects::sendNotification($this, $p,array('status'=>explode(',',$p->getTeam())),$this->getUser());
                }
                                
              }
            }
          }
        }
        else
        {                
          Doctrine_Query::create()
            ->update('Projects')
            ->set($key,$value)                  
            ->whereIn('id', explode(',',$request->getParameter('selected_items')))
            ->execute();
        }
        
      }
      
      $this->redirect_to($request->getParameter('redirect_to'));
    }        
  }
}
