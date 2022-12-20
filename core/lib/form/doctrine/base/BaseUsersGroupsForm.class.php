<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * UsersGroups form base class.
 *
 * @method UsersGroups getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseUsersGroupsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                                => new sfWidgetFormInputHidden(),
      'name'                              => new sfWidgetFormInputText(),
      'allow_view_all'                    => new sfWidgetFormInputText(),
      'allow_manage_projects'             => new sfWidgetFormInputText(),
      'allow_manage_tasks'                => new sfWidgetFormInputText(),
      'allow_manage_tickets'              => new sfWidgetFormInputText(),
      'allow_manage_users'                => new sfWidgetFormInputText(),
      'allow_manage_configuration'        => new sfWidgetFormInputText(),
      'allow_manage_tasks_viewonly'       => new sfWidgetFormInputText(),
      'allow_manage_discussions'          => new sfWidgetFormInputText(),
      'allow_manage_discussions_viewonly' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'allow_view_all'                    => new sfValidatorInteger(array('required' => false)),
      'allow_manage_projects'             => new sfValidatorInteger(array('required' => false)),
      'allow_manage_tasks'                => new sfValidatorInteger(array('required' => false)),
      'allow_manage_tickets'              => new sfValidatorInteger(array('required' => false)),
      'allow_manage_users'                => new sfValidatorInteger(array('required' => false)),
      'allow_manage_configuration'        => new sfValidatorInteger(array('required' => false)),
      'allow_manage_tasks_viewonly'       => new sfValidatorInteger(array('required' => false)),
      'allow_manage_discussions'          => new sfValidatorInteger(array('required' => false)),
      'allow_manage_discussions_viewonly' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_groups[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersGroups';
  }

}
