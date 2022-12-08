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
<?php

/**
 * Discussions form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DiscussionsForm extends BaseDiscussionsForm
{
  public function configure()
  {
    $projects = $this->getOption('projects');
    $sf_user = $this->getOption('sf_user');
    
    $this->widgetSchema['discussions_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('DiscussionsStatus')),array('class'=>'form-control input-large'));
    $this->setDefault('discussions_status_id', app::getDefaultValueByTable('DiscussionsStatus'));
            
    $this->widgetSchema['assigned_to'] = new sfWidgetFormChoice(array('choices'=>Users::getChoices(array_merge(array($sf_user->getAttribute('id')),array_filter(explode(',',$projects->getTeam()))),'discussions'),'expanded'=>true,'multiple'=>true));
    
    if(Users::hasAccess('edit','projects',$sf_user,$projects->getId()) and $this->getObject()->isNew())
    {
      $this->widgetSchema['users_id'] = new sfWidgetFormChoice(array('choices'=>Users::getChoices(array_merge(array($sf_user->getAttribute('id')),array_filter(explode(',',$projects->getTeam()))),'discussions_insert')),array('class'=>'form-control input-large'));
    }
    else
    {
      $this->widgetSchema['users_id'] = new sfWidgetFormInputHidden();
    }
    
    
    $this->widgetSchema['name']->setAttributes(array('size'=>'60','class'=>'form-control input-xlarge required autofocus'));
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor'));
    
    $this->widgetSchema['projects_id'] = new sfWidgetFormInputHidden();
                
    $this->widgetSchema->setLabels(array('discussions_status_id'=>'Status',
                                         'assigned_to'=>'Assigned To',
                                         'users_id'=>'Created By',                                                                                  
                                         ));   
  }
}
