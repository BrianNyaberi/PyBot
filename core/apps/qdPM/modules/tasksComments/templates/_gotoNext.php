<?php
/**
* WORK SMART
*/
?>
<table style="margin-left: 15px;">
  <tr>
    <td>
      <form id="form_goto_previous" action="<?php echo url_for('tasksComments/index') ?>" method="get">
        <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) . input_hidden_tag('tasks_id') . '<button type="submit" class="btn btn-xs btn-default" title="' . __('Go to previous task') . '" ><i class="fa fa-angle-left"></i></button>' ?>        
      </form>
    </td>
    <td style="padding-left: 5px;">
      <form id="form_goto_next" action="<?php echo url_for('tasksComments/index') ?>" method="get">
        <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) . input_hidden_tag('tasks_id') . '<button type="submit" class="btn btn-xs btn-default" title="' . __('Go to next task') . '" ><i class="fa fa-angle-right"></i></button>' ?>        
      </form>
    </td>
  </tr>
</table>
<script>
  $(function(){
  
    if($('#previous_tasks_id').val()>0)
    {
      $('#tasks_id',$('#form_goto_previous')).val($('#previous_tasks_id').val());
    }
    else
    {
      $('#form_goto_previous').css('display','none');
    }
    
    if($('#next_tasks_id').val()>0)
    {
      $('#tasks_id',$('#form_goto_next')).val($('#next_tasks_id').val());
    }
    else
    {
      $('#form_goto_next').css('display','none');
    }
  });
</script>
