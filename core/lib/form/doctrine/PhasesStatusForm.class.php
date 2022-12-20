<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * PhasesStatus form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhasesStatusForm extends BasePhasesStatusForm
{
  public function configure()
  {
    $this->widgetSchema['active'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('active',1);
    
    $this->widgetSchema['default_value'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('active',1);
      
    $this->widgetSchema['name']->setAttribute('class','form-control required');
    $this->widgetSchema['sort_order']->setAttribute('class','form-control input-xsmall');
    
    $this->widgetSchema->setLabels(array('sort_order'=>'Sort Order',                                                                                  
                                         'active'=>'Active?',
                                         'default_value'=>'Default?'));
  }
}
