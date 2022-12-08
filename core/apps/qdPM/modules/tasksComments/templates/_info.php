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
<ul class="comments-info-list">
<?php if($c['tasks_status_id']>0): ?>
  <li><?php echo '<span>' . __('Status') . ':</span> ' . app::getNameByTableId('TasksStatus',$c['tasks_status_id']) ?></li>
<?php endif ?>
<?php if($c['tasks_priority_id']>0): ?>
  <li><?php echo '<span>' . __('Priority') . ':</span> ' . app::getNameByTableId('TasksPriority',$c['tasks_priority_id']) ?></li>
<?php endif ?>
<?php if(strlen($c['due_date'])>0): ?>
  <li><?php echo '<span>' . __('Due Date') . ':</span> ' . app::dateTimeFormat($c['due_date']) ?></li>
<?php endif ?>
<?php if($c['worked_hours']>0): ?>
  <li><?php echo '<span>' . __('Work Hours') . ':</span> ' . $c['worked_hours'] ?></li>
<?php endif ?>
</ul>
