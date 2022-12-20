<?php
/**
* WORK SMART
*/
?>
<?php if(count($breadcrump)>0): ?>
<div id="breadcrump">
  <table>
    <tr>
      <?php foreach($breadcrump as $v){ echo '<td>' . link_to($v['name'],'tasksComments/index?projects_id=' . $sf_request->getParameter('projects_id') . '&tasks_id=' . $v['id']) . '</td><td>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</td>';} ?>
    </tr>  
  </table>
</div>
<?php endif ?>
