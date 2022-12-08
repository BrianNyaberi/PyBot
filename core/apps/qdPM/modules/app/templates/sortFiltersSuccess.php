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
      
