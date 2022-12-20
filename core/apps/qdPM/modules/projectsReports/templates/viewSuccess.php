<?php
/**
* WORK SMART
*/
?>
<h3 class="page-title"><?php echo $projects_reports->getName() ?></h3>

<?php echo button_tag_modalbox(__('Edit Report'),'projectsReports/edit?id=' .$sf_request->getParameter('id') . '&redirect_to=view')?>
<br><br>
  

<?php include_component('projects','listing',array('reports_id'=>$sf_request->getParameter('id'))) ?>
