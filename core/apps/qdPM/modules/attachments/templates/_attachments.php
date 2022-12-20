<?php
/**
* WORK SMART
*/
?>

<div id="attachmentsList"></div>
<div id="attachmentsLoading"></div>
<br>
<div> <input type="file" name="uploadify_file_upload" id="uploadify_file_upload" /> </div>


<?php

$timestamp = time();
$form_token = md5($sf_user->getAttribute('id') . $timestamp);

$html = '
  <script type="text/javascript">
      var is_file_uploading = null;
      
  		$(function() {
  			$("#uploadify_file_upload").uploadifive({
  				"auto"             : true,  
          "dnd"              : false, 
          "buttonClass"      : "btn btn-default btn-upload",
          "buttonText"       : "<i class=\"fa fa-upload\"></i> ' . __("Add Attachments") . '",				
  				"formData"         : {
  									   "timestamp" : ' . $timestamp . ',
  									   "token"     : "' .  $form_token . '"
  				                     },
  				"queueID"          : "attachmentsLoading",
          "fileSizeLimit" : "' . ((int)ini_get("post_max_size")<(int)ini_get("upload_max_filesize") ? (int)ini_get("post_max_size") : (int)ini_get("upload_max_filesize")) . 'MB",
  				"uploadScript"     : "' .  url_for("attachments/upload?bind_type=" . $bind_type . "&bind_id=" . $bind_id . "&uploadify=onUpload", true)  . '",
          "onUpload"         :  function(filesToUpload){
            is_file_uploading = true;
          },
  				"onQueueComplete" : function(file, data) {
            is_file_uploading = null  
            $(".uploadifive-queue-item.complete").fadeOut();
            $("#attachmentsList").append("' . __('Loading...') . '");
            $("#attachmentsList").load("' .  url_for('attachments/preview?bind_type=' . $bind_type . '&bind_id=' . $bind_id .'&token=' . $form_token ) . '"); 

          }
  			});
        
        $("#attachmentsList").load("' . url_for('attachments/preview?bind_type=' . $bind_type . '&bind_id=' . $bind_id ) . '");
                        
        $("button[type=submit]").bind("click",function(){                         
            if(is_file_uploading)
            {
              alert("' . __("Please wait. Files are loading.")  . '"); return false;
            }                           
          });
        
  		});
    </script>';
    
echo $html;
    
?>      


