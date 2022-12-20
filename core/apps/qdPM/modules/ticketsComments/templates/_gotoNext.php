<?php
/**
* WORK SMART
*/
?>
<table style="margin-left: 15px;">
  <tr>
    <td>
      <form id="form_goto_previous" action="<?php echo url_for('ticketsComments/index') ?>" method="get">
        <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) . input_hidden_tag('tickets_id') . '<button type="submit" class="btn btn-xs btn-default" title="' . __('Go to previous ticket') . '" ><i class="fa fa-angle-left"></i></button>' ?>        
      </form>
    </td>
    <td style="padding-left: 5px;">
      <form id="form_goto_next" action="<?php echo url_for('ticketsComments/index') ?>" method="get">
        <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) . input_hidden_tag('tickets_id') . '<button type="submit" class="btn btn-xs btn-default" title="' . __('Go to next ticket') . '" ><i class="fa fa-angle-right"></i></button>' ?>        
      </form>
    </td>
  </tr>
</table>
<script>
  $(function(){
  
    if($('#previous_item_id').val()>0)
    {
      $('#tickets_id',$('#form_goto_previous')).val($('#previous_item_id').val());
    }
    else
    {
      $('#form_goto_previous').css('display','none');
    }
    
    if($('#next_item_id').val()>0)
    {
      $('#tickets_id',$('#form_goto_next')).val($('#next_item_id').val());
    }
    else
    {
      $('#form_goto_next').css('display','none');
    }
  });
</script>
