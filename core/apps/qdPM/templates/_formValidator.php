<?php
/**
* WORK SMART
*/
?>
<script type="text/javascript">
$(document).ready(function(){  
  $.extend($.validator.messages, {required: '<?php echo __("This field is required!") ?>',email: '<?php echo __("Please enter a valid email address.") ?>'});
  
  $("#<?php echo $form_id ?>").validate({ignore:''});
}); 		
</script>

