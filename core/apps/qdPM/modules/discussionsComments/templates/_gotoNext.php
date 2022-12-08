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
<table style="margin-left: 15px;">
  <tr>
    <td>
      <form id="form_goto_previous" action="<?php echo url_for('discussionsComments/index') ?>" method="get">
        <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) . input_hidden_tag('discussions_id') .'<button type="submit" class="btn btn-xs btn-default" title="' . __('Go to previous discussion') . '" ><i class="fa fa-angle-left"></i></button>'  ?>        
      </form>
    </td>
    <td>
      <form id="form_goto_next" action="<?php echo url_for('discussionsComments/index') ?>" method="get">
        <?php echo input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')) . input_hidden_tag('discussions_id') . '<button type="submit" class="btn btn-xs btn-default" title="' . __('Go to next discussion') . '" ><i class="fa fa-angle-right"></i></button>' ?>        
      </form>
    </td>
  </tr>
</table>
<script>
  $(function(){
  
    if($('#previous_item_id').val()>0)
    {
      $('#discussions_id',$('#form_goto_previous')).val($('#previous_item_id').val());
    }
    else
    {
      $('#form_goto_previous').css('display','none');
    }
    
    if($('#next_item_id').val()>0)
    {
      $('#discussions_id',$('#form_goto_next')).val($('#next_item_id').val());
    }
    else
    {
      $('#form_goto_next').css('display','none');
    }
  });
</script>
