<?php
/**
* WORK SMART
*/
?>
<h1><?php echo __('Save Filter') ?></h1>

<form id="saveFilter" action="<?php echo url_for($sf_context->getModuleName() . '/doSaveFilter' . ((int)$sf_request->getParameter('projects_id')>0 ? '?projects_id=' . $sf_request->getParameter('projects_id') : ''))?>" method="post">
  <table class="contentTable">
    <tr>
      <td><?php echo __('Name') ?>: </td>
      <td><?php echo input_tag('name','',array('class'=>'required'))?></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="checkbox" value="1" name="is_default" id="is_default">  <label for="is_default"><?php echo __('Default?')?></label>
      <br><i><?php echo __('This filter will be used by default after login.') ?></i>
      </td>
    </tr>
  </table>
  <br>
  <input type="submit" class="btn" value="<?php echo __('Save') ?>">
</form>

<?php include_partial('global/formValidator',array('form_id'=>'saveFilter')); ?>
