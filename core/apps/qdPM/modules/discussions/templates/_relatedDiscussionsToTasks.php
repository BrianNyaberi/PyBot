<?php
/**
* WORK SMART
*/
?>
<?php if(count($discussions_list)>0): ?>
<h2><?php echo __('Related Discussions') ?></h2>

<table>
<?php 
$status = array();
foreach($discussions_list as $discussions): 
if($discussions['discussions_status_id']>0) $status[] = $discussions['discussions_status_id'];
?>
  <tr id="related_ticket_<?php echo $discussions['id'] ?>">
    <td><?php echo link_to((isset($discussions['DiscussionsTypes'])?$discussions['DiscussionsTypes']['name'] . ': ':'') . $discussions['name'] . (isset($discussions['DiscussionsStatus']) ? ' [' . $discussions['DiscussionsStatus']['name'] . ']':''), 'discussionsComments/index?discussions_id=' . $discussions['id'] . '&projects_id=' . $discussions['projects_id'],array('absolute'=>true)) ?></td>
    <td style="text-align: right;"><?php if(!$is_email) echo image_tag('icons/remove_link.png',array('title'=>__('Delete Related'),'style'=>'cursor:pointer','onClick'=>'removeRelated("related_ticket_' . $discussions['id'] . '","' . url_for('app/removeRelatedTaskWithDiscussions?discussions_id=' . $discussions['id'] . '&tasks_id=' . $sf_request->getParameter('tasks_id')) . '")')) ?></td>
  </tr>        
<?php endforeach ?>
</table>
<br>


<?php endif ?>
