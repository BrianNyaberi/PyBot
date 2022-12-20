<?php
/**
* WORK SMART
*/
?>
<?php
  switch($sf_context->getModuleName())
  {
    case 'usersProjectsReport':
    case 'projects': $t = 'ProjectsReports';
      break;
    case 'timeReport':  
    case 'usersTasksReport':  
    case 'ganttChart':
    case 'tasks': $t = 'UserReports';
      break;
    case 'tickets': $t = 'TicketsReports';
      break;
  }

  if($r = Doctrine_Core::getTable($t)->find($sf_request->getParameter('edit_user_filter')))
  {
    $name = $r->getName();
    $is_default = $r->getIsDefault();
  }
?>
<h1><?php echo __('Edit Filter') ?></h1>

<form id="saveFilter" action="<?php echo url_for($sf_context->getModuleName() . '/doSaveFilter?update_user_filter=' . $sf_request->getParameter('edit_user_filter') . ((int)$sf_request->getParameter('projects_id')>0 ? '&projects_id=' . $sf_request->getParameter('projects_id') : '') )?>" method="post">
  <table class="contentTable">
    <tr>
      <td><?php echo __('Name') ?>: </td>
      <td><?php echo input_tag('name',$name,array('class'=>'required'))?></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="checkbox" value="1" name="is_default" id="is_default" <?php echo ($is_default==1?'checked="checked"':'')?>>  <label for="is_default"><?php echo __('Default?')?></label>
      <br><i><?php echo __('This filter will be used by default after login.') ?></i>
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type="checkbox" value="1" name="update_values" id="update_values">  <label for="update_values"><?php echo __('Update Filter')?></label>
      <br><?php echo __('Current filters will be assigned to this filter.') ?>
      </td>
    </tr>
  </table>
  <br>
  <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) ?>
  
  <input type="submit" class="btn" value="<?php echo __('Save') ?>">
</form>

<?php include_partial('global/formValidator',array('form_id'=>'saveFilter')); ?>
