<?php
/**
* WORK SMART
*/
?>
<?php

class usersComponents extends sfComponents
{
  public function executeFilters(sfWebRequest $request)
  {  
    $m = array();
    
    $params = false;
            
    $m = app::getFilterMenuItemsByTable($m,'UsersGroups','Group','users/index',$params);
        
                                    
    $this->m = array(array('title'=>__('Filters'),'submenu'=>$m));
  }
  
  public function executeFiltersPreview(sfWebRequest $request)
  {
    $this->filter_by = $this->getUser()->getAttribute('users_filter');
    $this->params = false;
    
    $this->filter_tables = array('UsersGroups'=>'Group');
    
  }  
}
