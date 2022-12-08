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
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title"><?php echo $projects->getName() ?></h4>
</div>
<div class="modal-body">

<div class="ajax-modal-width-790"></div>

<div class="row">
    
  <div class="col-md-7">      
      <div class="itemInfo projectInfo">
        <div class="itemInfoContainer">
          <div class="itemDescription"><?php echo  replaceTextToLinks($projects->getDescription()) ?></div>
          <div id="extraFieldsInDescription"><?php echo ExtraFieldsList::renderDescriptionFileds('projects',$projects,$sf_user) ?></div>
          <div><?php include_component('attachments','attachmentsList',array('bind_type'=>'projects','bind_id'=>$projects->getId())) ?></div>
        </div>
      </div>
      
      
  </div>
    
  <div class="col-md-5">
  
      <div class="panel panel-info">
    		<div class="panel-heading">  			
    			 <h3 class="panel-title"><?php echo __('Details') ?></h3>  			
    		</div>
    		<div class="panel-body item-details">            
        <?php include_component('projects','details',array('projects'=>$projects)) ?>
        </div>
      </div>
  </div>    
    
</div> 

</div>
     

<div class="modal-footer">  
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>
</div>
