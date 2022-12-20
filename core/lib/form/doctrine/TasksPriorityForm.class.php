<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * TasksPriority form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TasksPriorityForm extends BaseTasksPriorityForm
{
  public function configure()
  {
    $this->widgetSchema['icon'] = new sfWidgetFormChoice(array('choices'=>app::getPriorityIconsChoices()), array('class'=>'form-control input-small'));
    
    $this->widgetSchema['active'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('active',1);
    
    $this->widgetSchema['default_value'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('default_value',0);

    $this->widgetSchema['name']->setAttribute('class','form-control  required');
    $this->widgetSchema['sort_order']->setAttribute('class','form-control input-xsmall');
    
    $this->widgetSchema->setLabels(array('sort_order'=>'Sort Order',
                                         'default_value'=>'Default?',
                                         'active'=>'Active?'));
  }
}
