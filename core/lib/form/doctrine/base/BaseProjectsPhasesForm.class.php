<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * ProjectsPhases form base class.
 *
 * @method ProjectsPhases getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjectsPhasesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'projects_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Projects'), 'add_empty' => false)),
      'phases_status_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PhasesStatus'), 'add_empty' => true)),
      'name'             => new sfWidgetFormInputText(),
      'due_date'         => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'projects_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Projects'), 'required' => false)),
      'phases_status_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PhasesStatus'), 'required' => false)),
      'name'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'due_date'         => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('projects_phases[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjectsPhases';
  }

}
