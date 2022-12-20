<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * ProjectsReports form base class.
 *
 * @method ProjectsReports getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjectsReportsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'users_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'name'               => new sfWidgetFormInputText(),
      'display_on_home'    => new sfWidgetFormInputText(),
      'projects_id'        => new sfWidgetFormTextarea(),
      'projects_type_id'   => new sfWidgetFormTextarea(),
      'projects_status_id' => new sfWidgetFormTextarea(),
      'in_team'            => new sfWidgetFormInputText(),
      'sort_order'         => new sfWidgetFormInputText(),
      'display_in_menu'    => new sfWidgetFormInputText(),
      'visible_on_home'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'users_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'display_on_home'    => new sfValidatorInteger(array('required' => false)),
      'projects_id'        => new sfValidatorString(array('required' => false)),
      'projects_type_id'   => new sfValidatorString(array('required' => false)),
      'projects_status_id' => new sfValidatorString(array('required' => false)),
      'in_team'            => new sfValidatorInteger(array('required' => false)),
      'sort_order'         => new sfValidatorInteger(array('required' => false)),
      'display_in_menu'    => new sfValidatorInteger(array('required' => false)),
      'visible_on_home'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('projects_reports[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjectsReports';
  }

}
