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
