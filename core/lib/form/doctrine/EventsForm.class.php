<?php
/**
* WORK SMART
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
