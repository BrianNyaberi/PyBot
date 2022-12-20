<?php
/**
* WORK SMART
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

