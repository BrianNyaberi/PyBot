<?php
/**
* WORK SMART
*/
?>
<div class="doctrine_pager">
<?php  
if(sfConfig::get('app_rows_limit')>0)
{         
  $url = $sf_context->getModuleName() . '/' . $sf_context->getActionName() . '?' . $url_params  . (strlen($url_params)>0 ? '&':'') . 'page=';
  
   
      
  if ($pager->haveToPaginate() && $sf_context->getModuleName()!='dashboard')
  { 
    
    echo '<div class="note note-warning">';      
    echo  __('Displaying') . ' ' . $pager->getFirstIndice()  . ' - ' .  $pager->getLastIndice();
    
    echo ' &nbsp;&nbsp; ' . link_to('&lt;', $url.$pager->getPreviousPage()) . ' ';
    $links = $pager->getLinks(); 
    
    foreach ($links as $page)
    { 
      echo ($page == $pager->getPage()) ? $page : link_to($page, $url.$page) ; 
      if ($page != $pager->getCurrentMaxLink()) echo ' - '; 
    }  
    
    echo ' &nbsp;&nbsp; ' . link_to('&gt;', $url.$pager->getNextPage()) .' &nbsp;&nbsp; ' ;
    
    echo  __('Total') . ': ' . $pager->getNbResults();
    echo '</div>';
  }
  elseif ($pager->haveToPaginate() && $sf_context->getModuleName()=='dashboard')
  {
    echo '<div class="note note-warning">'  . __('Displaying') . ' ' . $pager->getFirstIndice()  . ' - ' .  $pager->getLastIndice() . '&nbsp;&nbsp;' . link_to(__('View more'),$reports_action . '/view?id=' . $reports_id) . '</div>';    
  }
}
  
?>

  

</div>
<br />
