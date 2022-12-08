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
 * Events form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventsForm extends BaseEventsForm
{
  public function configure()
  {
    $this->widgetSchema['event_name'] = new sfWidgetFormInput();
    $this->widgetSchema['event_name']->setAttributes(array('size'=>'60','class'=>'form-control input-xlarge autofocus required'));
    $this->widgetSchema['details']->setAttributes(array('class'=>'form-control input-xlarge'));
    $this->setValidator('details',new sfValidatorString(array('required' => false)));
              
    $this->widgetSchema['start_date'] = new sfWidgetFormInput(array(),array('class'=>'form-control required'));    
    $this->widgetSchema['end_date'] = new sfWidgetFormInput(array(),array('class'=>'form-control required'));
            
    $this->widgetSchema['users_id'] = new sfWidgetFormInputHidden();
    
                
    $this->widgetSchema->setLabels(array('event_name'=>'Subject',
                                         'start_date'=>'Start Date',
                                         'end_date'=>'End Date'));
  }
}
