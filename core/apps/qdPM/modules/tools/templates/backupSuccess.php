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
<h3 class="page-title"><?php echo __('Backup') ?></h3>

<?php echo button_to(__('Backup'),'tools/doBackup',array('confirm'=>__('Are you sure?'),'class'=>'btn btn-primary'))  ?>

<table class="table table-striped table-bordered table-hover" id="table_backups">
  <thead>
    <tr>      
      <th width="100%"><?php echo __('File') ?></th>      
      <th><?php echo __('Size') ?></th>
      <th><?php echo __('Date') ?></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
<?php foreach($backups as $file): ?>
    <tr>          
      <td><?php echo $file; ?></td>
      <td><?php echo number_format(filesize(sfConfig::get('sf_web_dir') . '/backups/' . $file))  . ' ' . __('bytes'); ?></td>
      <td><?php echo app::dateTimeFormat('',filemtime(sfConfig::get('sf_web_dir') . '/backups/' . $file)); ?></td>
      
      <td><?php echo button_to(__('Restore'),'tools/doRestore?restore_file=' . $file,array('confirm'=>__('Are you sure?'),'class'=>'btn btn-default'))?></td>
      <td><?php echo button_to(__('Download'),'tools/backup?download_file=' . $file,array('class'=>'btn btn-default'))?></td>
      <td><?php echo button_to(__('Delete'),'tools/backup?delete_file=' . $file,array('confirm'=>__('Are you sure?'),'class'=>'btn btn-default'))?></td>
    </tr>
<?php endforeach ?> 
<?php if(count($backups)==0) echo '<tr><td colspan="20">' . __('No Records Found') . '</td></tr>';?>  
   <tbody> 
</table>

<?php echo __('Backup Directory') . ': ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/backups/' ?><br> 

<script>

<?php
if(count($backups)>0){
echo '$("#table_backups").dataTable({
        "iDisplayLength": 5,
        "sPaginationType": "bootstrap",
        "bSort": false,
        "bFilter":false,
        "bLengthChange":false,
        "oLanguage": {                    
                          "oPaginate": {
                              "sPrevious": "' .  __('Previous Page') . '",
                              "sNext": "' .  __('Next Page') . '"
                          },
                          "sInfo": "' .  __('Displaying') .  ' _START_ - _END_, ' . __('Total') . ': _TOTAL_ "
                      }     
        });';
}  
?>  
</script> 

