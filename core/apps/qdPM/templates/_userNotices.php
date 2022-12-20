<?php
/**
* WORK SMART
*/
?>
<?php 
  if(is_array($userNotices))
  {
    switch($userNotices['type'])
    {
      case 'error':
          echo '<div id="userAlertContainer" class="alert alert-danger"><strong>' . __('Error') . ':</strong> ' . $userNotices['text'] . '</div>';
        break;
      case 'warning':
          echo '<div id="userAlertContainer" class="alert alert-warning"><strong>' . __('Warning') . ':</strong> ' . $userNotices['text'] . '</div>';
        break;
      case 'info':
          echo '<div id="userAlertContainer" class="alert alert-info">' . $userNotices['text'] . '</div>';
        break;  
    }
  }
  else
  {
    echo '<div class="alert alert-info">' . $userNotices . '</div>';        
  }
 ?>


<script type="text/javascript">
  $(function() {
    $("#userAlertContainer").delay(10000).fadeOut();
  });
</script>
