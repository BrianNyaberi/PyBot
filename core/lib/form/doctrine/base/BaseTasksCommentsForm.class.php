<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * TasksComments form base class.
 *
 * @method TasksComments getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTasksCommentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'tasks_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tasks'), 'add_empty' => false)),
      'created_by'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'tasks_status_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksStatus'), 'add_empty' => true)),
      'tasks_priority_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksPriority'), 'add_empty' => true)),
      'due_date'          => new sfWidgetFormDate(),
      'worked_hours'      => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'created_at'        => new sfWidgetFormDateTime(),
      'progress'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'tasks_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tasks'), 'required' => false)),
      'created_by'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'tasks_status_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksStatus'), 'required' => false)),
      'tasks_priority_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksPriority'), 'required' => false)),
      'due_date'          => new sfValidatorDate(array('required' => false)),
      'worked_hours'      => new sfValidatorNumber(array('required' => false)),
      'description'       => new sfValidatorString(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
      'progress'          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tasks_comments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TasksComments';
  }

}
