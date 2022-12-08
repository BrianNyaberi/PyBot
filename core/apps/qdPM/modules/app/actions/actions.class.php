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
 * app actions.
 *
 * @package    sf_sandbox
 * @subpackage app
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class appActions extends sfActions
{ 
   
  public function executeRemoveRelatedTicketWithTask(sfWebRequest $request)
  {
    if($t = Doctrine_Core::getTable('Tasks')->find($request->getParameter('tasks_id')))
    {
      $t->setTicketsId(null);
      $t->save();
    }
        
    exit();    
  }
  
  public function executeRemoveRelatedTaskWithDiscussions(sfWebRequest $request)
  {
    if($t = Doctrine_Core::getTable('Tasks')->find($request->getParameter('tasks_id')))
    {
      $t->setDiscussionId(null);
      $t->save();
    }
    
    exit();    
  }
    
   
  public function executeMultipleDelete(sfWebRequest $request) 
  {
  
  }
  
  public function executeDoMultipleDelete(sfWebRequest $request) 
  {
    $access = Users::getAccessSchema($request->getParameter('table'),$this->getUser());
    
    if(!$access['delete'])
    {
      $this->redirect('accessForbidden/index');
    }
  
    if($selected_items = $request->getParameter('selected_items'))
    {
      if(strlen($selected_items)>0)
      {                         
        Doctrine_Query::create()
          ->delete()
          ->from($request->getParameter('table'))
          ->whereIn('id', explode(',',$selected_items))
          ->execute();
          
        Attachments::resetAttachments();  
          
      }
    }
    
    $this->redirect($request->getParameter('table').'/index' . (($projects_id = $request->getParameter('projects_id'))>0 ? '?projects_id=' . $projects_id:''));
  }
   
  public function executeSortItems(sfWebRequest $request)
  {
    $t = $request->getParameter('t');
    
    
    switch($t)
    {
     case 'projectsReports':
     case 'userReports':
     case 'ticketsReports':
     case 'discussionsReports':
        $this->itmes = Doctrine_Core::getTable($t)
          ->createQuery()
          ->addWhere('users_id=?',$this->getUser()->getAttribute('id'))      
          ->orderBy('sort_order, name')
          ->execute();
      break;          
     case 'extraFields':
          $this->itmes = Doctrine_Core::getTable($t)
            ->createQuery('a')
            ->addWhere('bind_type=?',$request->getParameter('bind_type'))            
            ->orderBy('sort_order, name')
            ->execute();                        
        break;      
      case 'tasksStatus':      
      case 'ticketsStatus':
          $this->itmes = Doctrine_Core::getTable($t)
            ->createQuery('a')
            ->orderBy('group desc, sort_order, name')
            ->execute();
            
            $this->setTemplate('sortGroupedItems');
        break;
      default:
          $this->itmes = Doctrine_Core::getTable($t)
            ->createQuery('a')
            ->orderBy('sort_order, name')
            ->execute();
        break;
    }        
  }
  
  public function executeSortItemsProcess(sfWebRequest $request)
  {
    $t = $request->getParameter('t');
    
    $list = $request->getParameter('list');
    $list = json_decode($list);
                
    $sort_order = 0;
    foreach($list as $ojb)
    {
      Doctrine_Query::create()
      ->update($t)
      ->set('sort_order', $sort_order)
      ->where('id = ?', $ojb->id)
      ->execute();

      $sort_order++;
    }

    exit();
  }
  
  public function executeSortGroupedItemsProcess(sfWebRequest $request)
  {
    $t = $request->getParameter('t');    
    
    foreach(app::getStatusGroupsChoices() as $k=>$name)
    {
      $list = $request->getParameter('sorted_items_' . $k);
      $list = json_decode($list);
      
      $sort_order = 0;
      foreach($list as $ojb)
      {
        if($t=='tasksStatus') $t='tasks_status';
        if($t=='ticketsStatus') $t='tickets_status';
                
        $sql = "update " . $t . " ts set ts.group='" . $k . "', ts.sort_order='" . $sort_order. "' where ts.id='" . $ojb->id . "'";
            
        $connection = Doctrine_Manager::connection();
        $connection->execute($sql);
          
        $sort_order++;
      }
    }

    exit();
  }
  
  public function executeCopyAttachments(sfWebRequest $request)
  {
    if(count($attachments_ids = explode(',',$request->getParameter('attachments')))>0)
    {
      
      $attachments = Doctrine_Core::getTable('Attachments')
                    ->createQuery()
                    ->whereIn('id',$attachments_ids)
                    ->execute();
      foreach($attachments as $a)
      {                                                             
        
        if(Doctrine_Core::getTable('Attachments')->createQuery()->addWhere('SUBSTRING(file,8)=?',substr($a->getFile(),7))->addWhere('bind_type=?', $request->getParameter('to'))->addWhere('bind_id=?',-$this->getUser()->getAttribute('id'))->count()==0)        
        {
          $new_filename = rand(111111,999999)  . '-' . substr($a->getFile(),7);            
          copy(sfConfig::get('sf_upload_dir') . '/attachments/'  . $a->getFile(),sfConfig::get('sf_upload_dir') . '/attachments/'  . $new_filename);
                          
          $aa = new Attachments();
          $aa->setFile($new_filename);
          $aa->setInfo($a->getInfo());          
          $aa->setBindType($request->getParameter('to'));            
          $aa->setBindId(-$this->getUser()->getAttribute('id'));          
          $aa->save();
        }
      }
    }
    
    exit();
  }   
}
