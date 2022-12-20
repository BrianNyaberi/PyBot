<?php
/**
* WORK SMART
*/
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo __('My Dashboard') ?></h4>
</div>





<form action="<?php echo url_for('dashboard/configure')?>" method="post">

<div class="modal-body">
<?php echo __('Just select reports which you want to display on dashboard') ?><br>

<input type="hidden" name="sf_method" value="put" />
<?php

if(count($projects_reports)==0 and count($user_reports)==0 and count($tickets_reports)==0 and count($discussions_reports)==0) echo __('No Reports Found');

if(count($projects_reports)>0)
{
  echo '<div style="margin-top: 10px;"><b>' . __('Projects Reports') . '</b><div>';
  
  foreach($projects_reports as $r)
  {
    echo input_checkbox_tag('projects_reports[]',$r->getId(),array('checked'=>($r->getDisplayOnHome()==1?true:false))) . ' <label for="projects_reports_' . $r->getId() . '">' . $r->getName() . '</label><br>';
  }
}

if(count($user_reports)>0)
{
  echo '<div style="margin-top: 10px;"><b>' . __('Tasks Reports') . '</b><div>';
  
  foreach($user_reports as $r)
  {
    echo input_checkbox_tag('user_reports[]',$r->getId(),array('checked'=>($r->getDisplayOnHome()==1?true:false))) . ' <label for="user_reports_' . $r->getId() . '">' . $r->getName() . '</label><br>';
  }
}

if(count($tickets_reports)>0)
{
  echo '<div style="margin-top: 10px;"><b>' . __('Tickets Reports') . '</b><div>';
  
  foreach($tickets_reports as $r)
  {
    echo input_checkbox_tag('tickets_reports[]',$r->getId(),array('checked'=>($r->getDisplayOnHome()==1?true:false))) . ' <label for="tickets_reports_' . $r->getId() . '">' . $r->getName() . '</label><br>';
  }
}

if(count($discussions_reports)>0)
{
  echo '<div style="margin-top: 10px;"><b>' . __('Discussions Reports') . '</b><div>';
  
  foreach($discussions_reports as $r)
  {
    echo input_checkbox_tag('discussions_reports[]',$r->getId(),array('checked'=>($r->getDisplayOnHome()==1?true:false))) . ' <label for="discussions_reports_' . $r->getId() . '">' . $r->getName() . '</label><br>';
  }
}

if(count($projects_reports)>0 or count($user_reports)>0 or count($tickets_reports)>0 or count($discussions_reports)>0)  echo ajax_modal_template_footer(__('Update'));

?>
</form>
