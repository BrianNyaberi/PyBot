<?php
/**
* WORK SMART
*/
?>
<?php 

$search = $sf_request->getParameter('search');

if(isset($search['keywords']))
{
  echo  '<div class="alert alert-info alert-search-result">' . __('Search result for') . ' <b>"' . $search['keywords'] . '"</b>&nbsp;&nbsp;&nbsp;<a href="' . url_for($sf_context->getModuleName().'/index'. ($sf_request->hasParameter('projects_id') ? '?projects_id=' . $sf_request->getParameter('projects_id'):'')) . '">' . __('Reset') . '</a></div>';
}

 

?>
