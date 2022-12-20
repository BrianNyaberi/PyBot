<?php
/**
* WORK SMART
*/
?>
<?php if($is_related): ?>

<ul class="list-group">
  <li class="list-group-item">
    <?php echo __($title) . ': <a href="#" onClick="copyToRelated(\'' . $form_name . '\',\'name\')">' . __('Name') . '</a> | <a href="#" onClick="copyToRelated(\'' . $form_name . '\',\'description\')">' . __('Description') . '</a> | <a id="copy_attachments_link" href="#" onClick="copyToRelated(\'' . $form_name . '\',\'attachments\',\'' . url_for('app/copyAttachments?to=' . $form_name) . '\',\'' . url_for('attachments/preview?bind_type=' . $form_name . '&bind_id=0') . '\')">' . __('Attachments') . '</a>'; ?>
  </li>
</ul>  

<?php endif ?>

<script>
  
  if(!document.getElementById('item_attachments'))
  {
    $('#copy_attachments_link').css('display','none');
  }
</script>
