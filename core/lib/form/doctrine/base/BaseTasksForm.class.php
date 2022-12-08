<?php
/**
*qdPM
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@qdPM.net so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade qdPM to newer
* versions in the future. If you wish to customize qdPM for your
* needs please refer to http://www.qdPM.net for more information.
*
* @copyright  Copyright (c) 2009  Sergey Kharchishin and Kym Romanets (http://www.qdpm.net)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
?>
<?php

/**
 * Tasks form base class.
 *
 * @method Tasks getObject() Returns the current form's model object
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTasksForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'projects_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Projects'), 'add_empty' => false)),
      'tasks_status_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksStatus'), 'add_empty' => true)),
      'tasks_priority_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksPriority'), 'add_empty' => true)),
      'tasks_type_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksTypes'), 'add_empty' => true)),
      'tasks_label_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksLabels'), 'add_empty' => true)),
      'tasks_groups_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TasksGroups'), 'add_empty' => true)),
      'projects_phases_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ProjectsPhases'), 'add_empty' => true)),
      'versions_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Versions'), 'add_empty' => true)),
      'created_by'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'name'               => new sfWidgetFormInputText(),
      'description'        => new sfWidgetFormTextarea(),
      'assigned_to'        => new sfWidgetFormInputText(),
      'estimated_time'     => new sfWidgetFormInputText(),
      'due_date'           => new sfWidgetFormDate(),
      'created_at'         => new sfWidgetFormDateTime(),
      'tickets_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tickets'), 'add_empty' => true)),
      'closed_date'        => new sfWidgetFormDate(),
      'discussion_id'      => new sfWidgetFormInputText(),
      'start_date'         => new sfWidgetFormDate(),
      'progress'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'projects_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Projects'), 'required' => false)),
      'tasks_status_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksStatus'), 'required' => false)),
      'tasks_priority_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksPriority'), 'required' => false)),
      'tasks_type_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksTypes'), 'required' => false)),
      'tasks_label_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksLabels'), 'required' => false)),
      'tasks_groups_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TasksGroups'), 'required' => false)),
      'projects_phases_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ProjectsPhases'), 'required' => false)),
      'versions_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Versions'), 'required' => false)),
      'created_by'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'        => new sfValidatorString(array('required' => false)),
      'assigned_to'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'estimated_time'     => new sfValidatorNumber(array('required' => false)),
      'due_date'           => new sfValidatorDate(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'tickets_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tickets'), 'required' => false)),
      'closed_date'        => new sfValidatorDate(array('required' => false)),
      'discussion_id'      => new sfValidatorInteger(array('required' => false)),
      'start_date'         => new sfValidatorDate(array('required' => false)),
      'progress'           => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tasks[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tasks';
  }

}
