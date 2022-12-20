<?php
/**

*/
?>
<?php

class appComponents extends sfComponents
{
  public function executeCopyToRelated(sfWebRequest $request)
  {
    $this->is_related = false;
    
    if($request->hasParameter('related_tasks_id') or $request->hasParameter('related_tickets_id') or $request->hasParameter('related_discussions_id'))
    {
      $this->is_related = true;          
    }  
    
    switch(true)
    {
      case $request->hasParameter('related_tasks_id'): $this->title='Copy from task';
        break;
      case $request->hasParameter('related_tickets_id'): $this->title='Copy from ticket';
        break;
      case $request->hasParameter('related_discussions_id'): $this->title='Copy from discussion';
        break;
    }      
  }

  public function executeSearchMenu()
  {
  
  }
  
  public function executeSearchMenuSimple()
  {
  
  }
  
  public function executeOrderByMenu(sfWebRequest $request)
  {
    $params = ($request->hasParameter('projects_id')?'&projects_id=' . $request->getParameter('projects_id'):'');
  
    $m = array();
    $m[] = array('title'=>__('Date Added'),'url'=>$this->module . '/index?set_order=date_added' . $params);    
    switch($this->module)
    {
      case 'projects':          
          $m[] = array('title'=>__('Name'),'url'=>$this->module . '/index?set_order=name');          
          if(app::countItemsByTable('ProjectsStatus')>0)   $m[] = array('title'=>__('Status'),'url'=>$this->module . '/index?set_order=status');
          if(app::countItemsByTable('ProjectsTypes')>0)    $m[] = array('title'=>__('Type'),'url'=>$this->module . '/index?set_order=type');          
        break;
      case 'tasks':          
          $m[] = array('title'=>__('Name'),'url'=>$this->module . '/index?set_order=name' . $params);
          if(app::countItemsByTable('TasksPriority')>0) $m[] = array('title'=>__('Priority'),'url'=>$this->module . '/index?set_order=priority' . $params);
          if(app::countItemsByTable('TasksStatus')>0)   $m[] = array('title'=>__('Status'),'url'=>$this->module . '/index?set_order=status' . $params);
          if(app::countItemsByTable('TasksTypes')>0)    $m[] = array('title'=>__('Type'),'url'=>$this->module . '/index?set_order=type' . $params);
          if(app::countItemsByTable('TasksLabels')>0)   $m[] = array('title'=>__('Label'),'url'=>$this->module . '/index?set_order=label' . $params);
        break;
      case 'tickets':          
          $m[] = array('title'=>__('Name'),'url'=>$this->module . '/index?set_order=name' . $params);          
          if(app::countItemsByTable('TicketsStatus')>0)   $m[] = array('title'=>__('Status'),'url'=>$this->module . '/index?set_order=status' . $params);
          if(app::countItemsByTable('TicketsTypes')>0)    $m[] = array('title'=>__('Type'),'url'=>$this->module . '/index?set_order=type' . $params);          
          if(app::countItemsByTable('Departments')>0)   $m[] = array('title'=>__('Department'),'url'=>$this->module . '/index?set_order=department' . $params);
        break;
      case 'discussions':          
          $m[] = array('title'=>__('Name'),'url'=>$this->module . '/index?set_order=name' . $params);          
          if(app::countItemsByTable('DiscussionsStatus')>0)   $m[] = array('title'=>__('Status'),'url'=>$this->module . '/index?set_order=status' . $params);
                              
        break;
    }
    
    $current_order = app::getListingOrderTitle($this->module,$this->getUser(),$request->getParameter('projects_id'));
    foreach($m as $k=>$v)
    {
      if($v['title']==$current_order) $m[$k]['title'] = '<strong>' . $m[$k]['title'] . '</strong>'; 
    }
     
    
    $this->m = array(array('title'=>__('Order By'),'submenu'=>$m));
  }
  
  public function executeExtraFieldsInSearch(sfWebRequest $request)
  {
    $this->extr_fields = $q = Doctrine_Core::getTable('ExtraFields')->createQuery()
                              ->addWhere('bind_type=?',$this->type)
                              ->whereIn('type',array('text','textarea','textarea_wysiwyg'))
                              ->orderBy('sort_order,name')
                              ->execute();  
  }
}
