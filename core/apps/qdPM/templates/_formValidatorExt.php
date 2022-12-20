<?php
/**
* WORK SMART
*/
?>
<script type="text/javascript">
$(document).ready(function(){  
  $.extend($.validator.messages, {required: '<?php echo __("This field is required!") ?>'});
  
  $("#<?php echo $form_id ?>").validate({ignore:'',invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = '<div class="alert alert-danger"><?php echo __("Some fields are required. They have been highlighted above.") ?></div>';
				$("#form_error_handler").fadeIn().html(message).delay(2000).fadeOut();								
			} 
		}});
}); 		
</script>

<div id="form_error_handler" style="display:none"></div>
