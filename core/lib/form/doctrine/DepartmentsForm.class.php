<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Departments form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DepartmentsForm extends BaseDepartmentsForm
{
  public function configure()
  {
    $this->widgetSchema['users_id'] = new sfWidgetFormChoice(array('choices'=>Users::getChoices(array(),'tickets')),array('class'=>'form-control required'));
    
    
    $this->widgetSchema['active'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    
    $this->widgetSchema['name']->setAttribute('class','form-control  required');
    $this->widgetSchema['sort_order']->setAttribute('class','form-control input-xsmall');                        
    
    $this->setDefault('active', 1);
      
    $this->widgetSchema->setLabels(array('users_id' => 'Assigned To', 
                                         'sort_order' => 'Sort Order',
                                        ));
  }
}
