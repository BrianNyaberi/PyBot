<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Phases form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhasesForm extends BasePhasesForm
{
  public function configure()
  {
    $this->widgetSchema['name']->setAttribute('class','form-control required');
    $this->widgetSchema['default_values']->setAttribute('class','form-control required');        
  }
}
