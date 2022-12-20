<?php
/**
* WORK SMART
*/
?>
<div style="max-height: 500px; width: 550px; overflow: auto; padding-right: 20px;">

<?php foreach($users_list as $group=>$users): $rnd = rand(1111,9999)?>
<div style="padding: 13px 0 3px 3px; font-weight: bold; font-size: 14px;"><?php echo $group ?></div>

<table class="tableBorder trHover" style="width: 100%;">
  <tr>
    <th width="10"><?php echo input_checkbox_tag('check_all_users_' . $rnd,'',array('class'=>'check_all_users')) ?></th>
    <th><b><?php echo __('User')?></b></th>

  </tr>
  <?php foreach($users as $id=>$name): ?>
    <tr>
      <td><?php echo input_checkbox_tag('projects[team][]',$id,array('class'=>'rnd' . $rnd . ' projects_team','checked'=>in_array($id,$in_team))) ?></td>
      <td><label for="projects_team_<?php echo $id ?>"><?php echo $name ?></label></td>

    </tr>
  <?php endforeach ?>
</table>

<?php endforeach ?>

</div>
