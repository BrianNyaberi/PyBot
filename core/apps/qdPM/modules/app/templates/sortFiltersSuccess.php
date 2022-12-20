<?php
/**
* WORK SMART
*/
?>
<h1><?php echo __('Sort Filters') ?></h1>

<?php echo __('Just move an item up or down.') ?>

<?php
  switch($sf_context->getModuleName())
  {
    case 'usersProjectsReport':
    case 'projects':
        $t = 'ProjectsReports';         
      break;
    case 'usersTasksReport':  
    case 'ganttChart':
    case 'timeReport':
    case 'tasks':
        $t = 'UserReports';         
      break;
    case 'tickets':
        $t = 'TicketsReports';         
      break;
    case 'discussions':
        $t = 'DiscussionsReports';         
      break;
        
  }
  
  $itmes = Doctrine_Core::getTable($t)
          ->createQuery()
          ->addWhere('report_type=?',$sf_request->getParameter('sort_filters'))
          ->addWhere('users_id=?',$sf_user->getAttribute('id'))
          ->orderBy('sort_order, name')
          ->fetchArray();
?>

<ul id="sorted_items" class='droptrue'>
  <?php
    foreach($itmes as $v)
    {
      echo '<li id="field_' . $v['id'] . '">' . $v['name'] . '</li>';
    }
  ?>
</ul>

<?php echo button_to_tag(__('Save'),$sf_context->getModuleName() . '/index' . ($sf_request->hasParameter('projects_id')?'?projects_id=' . $sf_request->getParameter('projects_id'):'')) ?>

<script>
$(function() {
	$( "ul.droptrue" ).sortable({
		connectWith: "ul",
		update: function(event,ui){ droppableOnUpdate('<?php echo url_for("app/SortItemsProcess?t=" . $t)?>') }
	});
});
</script>      
      
