<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * ProjectsReports form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjectsReportsForm extends BaseProjectsReportsForm
{
  public function configure()
  {  
    $this->widgetSchema['display_on_home'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('display_on_home', false);
    
    $this->widgetSchema['display_in_menu'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('display_in_menu', false);
              
    $this->widgetSchema['users_id'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['name']->setAttributes(array('class'=>'form-control autofocus required'));
                
    $this->widgetSchema->setLabels(array('display_on_home'=>'Display on dashboard'));
            
    unset($this->widgetSchema['sort_order']);
    unset($this->validatorSchema['sort_order']);
  }
}
