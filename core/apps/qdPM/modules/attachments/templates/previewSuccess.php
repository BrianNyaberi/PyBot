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
<?php foreach($attachments as $a): 

$file_path = sfConfig::get('sf_upload_dir') . '/attachments/' . $a->getFile();

?>
  <div id="attachedFile<?php echo $a->getId() ?>">
    <table style="width: 100%" class="table">
      <tr>
        <td colspan="2" ><?php echo Attachments::getFileIcon($a->getFile())  . ' ' . (is_file($file_path) ? (getimagesize($file_path) ? link_to( substr($a->getFile(),7), 'attachments/view?id=' . $a->getId(), array('target'=>'_blank', 'absolute'=>true)) :Attachments::getLink($a)):substr($a->getFile(),7)) ?></td>
      </tr>                                                                                     
      </tr>
        <?php if($a->getBindType()!='wiki'):?>
        <td valign="top" style="border-top: 0;"><?php echo  input_tag('attachments_info[' . $a->getId() . ']',$a->getInfo(),array('class'=>'form-control','placeholder'=>__('Description')))?></td>
        <?php endif ?>
        
        <td style="border-top: 0;"><a href="#" onClick="return deleteAttachments(<?php echo $a->getId() ?>,'<?php echo url_for('attachments/delete?id=' . $a->getId()) ?>')"><?php echo __('Delete') ?></a></td>
      </tr> 
      <?php if($a->getBindType()=='wiki') echo '<tr><td colspan="3">[' . url_for('attachments/view?id='.$a->getId(),true). ' ' . substr($a->getFile(),7) . ']</td></tr>' ?>       
    </table>
  </div>
<?php endforeach ?>
