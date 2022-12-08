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

    
<div class="row">   
  <div class="col-md-4"> 
    <?php if($action_name=='index'){ ?>
      <form action="<?php echo url_for('timeReport/' . $action_name) ?>" method="post">
      <table style="width: 100%; margin-top: 3px;">
        <tr>
          <td style="width: 100px"><?php echo __('User')?>:&nbsp;</td>
          <td><?php echo select_tag('filter_by[CommentCreatedBy]',(isset($filter_by['CommentCreatedBy'])?$filter_by['CommentCreatedBy']:''),array('choices'=>Users::getChoices(array(),'tasks_comments_insert',true)),array('onChange'=>'this.form.submit()', 'class'=>'form-control'))?></td>          
        </tr>
      </table>
      <?php echo ($sf_request->hasParameter('projects_id')? input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')):'') ?>
      </form>
    <?php }else {?>
    
      <table>
        <tr>
          <td><?php echo __('User')?>:&nbsp;</td>
          <td><?php $user = $sf_user->getAttribute('user'); echo $user->getName() ?></td>          
        </tr>
      </table>
    <?php } ?>

      <form action="<?php echo url_for('timeReport/'. $action_name) ?>" method="post">
      <table style="width: 100%; margin-top: 3px;">
        <tr>
          <td style="width: 100px"><?php echo __('Discrepancy:')?>&nbsp;</td>
          <td><?php echo select_tag('filter_by[TimeDiscrepancy]',(isset($filter_by['TimeDiscrepancy'])?$filter_by['TimeDiscrepancy']:''),array('choices'=>array(''=>__('All'),'under'=>__('Under'),'over'=>__('Over'),'ok'=>__('Ok'))),array('onChange'=>'this.form.submit()', 'class'=>'form-control'))?></td>          
        </tr>
      </table>
      <?php echo ($sf_request->hasParameter('projects_id')? input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')):'') ?>
      </form>
    </div>
    
      <form action="<?php echo url_for('timeReport/' . $action_name) ?>" method="post">
      <div class="col-md-8">
      <table>
        <tr>
          <td style="width:40px"><?php echo __('From')?>:&nbsp;</td>
          <td>            
            <div class="input-group input-medium date datepicker"><?php echo input_tag('filter_by[CommentCreatedFrom]',(isset($filter_by['CommentCreatedFrom'])?$filter_by['CommentCreatedFrom']:date('Y-m-d')),array('class'=>'form-control datepicker'))?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>  
          </td>
        </tr>
       </table>

       <table>
        <tr> 
          <td style="width:40px; "><?php echo __('To')?>:&nbsp;</td>
          <td>
            <div class="input-group input-medium date datepicker"><?php echo input_tag('filter_by[CommentCreatedTo]',(isset($filter_by['CommentCreatedTo'])?$filter_by['CommentCreatedTo']:''),array('class'=>'form-control datepicker'))?><span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>  
          </td>
          <td>&nbsp;<input type="submit" class="btn btn-default" value="<?php echo  __('Filter By Dates')?>"></td>
        </tr>
      </table>
      </div>
      <?php echo ($sf_request->hasParameter('projects_id')? input_hidden_tag('projects_id',$sf_request->getParameter('projects_id')):'') ?>
      </form>
      
    
</div>    
    
    
