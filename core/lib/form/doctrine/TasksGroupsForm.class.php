<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * TasksGroups form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TasksGroupsForm extends BaseTasksGroupsForm
{
  public function configure()
  {
    $this->widgetSchema['projects_id'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['name']->setAttribute('class','form-control input-medium required');
  }
}
