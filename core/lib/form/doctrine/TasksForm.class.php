<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Tasks form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TasksForm extends BaseTasksForm
{
  public function configure()
  {
    $projects = $this->getOption('projects');
    $sf_user = $this->getOption('sf_user');
  
    $this->widgetSchema['tasks_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksStatus')),array('class'=>'form-control input-large'));
    $this->setDefault('tasks_status_id', app::getDefaultValueByTable('TasksStatus'));
    
    $this->widgetSchema['tasks_priority_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksPriority')),array('class'=>'form-control input-large'));
    $this->setDefault('tasks_priority_id', app::getDefaultValueByTable('TasksPriority'));
    
    $this->widgetSchema['tasks_type_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksTypes')),array('class'=>'form-control input-large'));
    $this->setDefault('tasks_type_id', app::getDefaultValueByTable('TasksTypes'));
    
    $this->widgetSchema['tasks_label_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksLabels')),array('class'=>'form-control input-large'));
    $this->setDefault('tasks_label_id', app::getDefaultValueByTable('TasksLabels'));
    
    $this->widgetSchema['tasks_groups_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksGroups',true,$projects->getId())),array('class'=>'form-control input-large'));    
    $this->widgetSchema['projects_phases_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('ProjectsPhases',true,$projects->getId())),array('class'=>'form-control input-large'));
    $this->widgetSchema['versions_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('Versions',true,$projects->getId())),array('class'=>'form-control input-large'));
    
    $this->widgetSchema['progress'] =  new sfWidgetFormChoice(array('choices' => Tasks::getProgressChoices()),array('class'=>'form-control input-small'));
    
  
    $this->widgetSchema['assigned_to'] = new sfWidgetFormChoice(array('choices'=>Users::getChoices(array_merge(array($sf_user->getAttribute('id')),array_filter(explode(',',$projects->getTeam()))),'tasks'),'expanded'=>true,'multiple'=>true));
    
    if(Users::hasAccess('edit','projects',$sf_user,$projects->getId()) and $this->getObject()->isNew())
    {
      $this->widgetSchema['created_by'] = new sfWidgetFormChoice(array('choices'=>Users::getChoices(array_merge(array($sf_user->getAttribute('id')),array_filter(explode(',',$projects->getTeam()))),'tasks_insert')),array('class'=>'form-control input-large'));
    }
    else
    {
      $this->widgetSchema['created_by'] = new sfWidgetFormInputHidden();
    }
      
    $this->widgetSchema['name']->setAttributes(array('class'=>'form-control input-xlarge autofocus required'));
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor'));
              
    $this->widgetSchema['estimated_time']->setAttributes(array('class'=>'form-control input-small'));
    
    $this->widgetSchema['start_date'] = new sfWidgetFormInput(array(),array('class'=>'form-control datepicker'));    
    $this->widgetSchema['due_date'] = new sfWidgetFormInput(array(),array('class'=>'form-control datepicker'));
                
    $this->widgetSchema['projects_id'] = new sfWidgetFormInputHidden();                    
        
    $this->widgetSchema['closed_date'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();
    $this->setDefault('created_at', date('Y-m-d H:i:s'));
                 
    $this->widgetSchema->setLabels(array('tasks_priority_id'=>'Priority',
                                         'tasks_type_id'=>'Type',
                                         'tasks_label_id'=>'Label',
                                         'projects_phases_id'=>'Phase',
                                         'versions_id'=>'Version',
                                         'tasks_groups_id'=>'Group',
                                         'tasks_status_id'=>'Status',
                                         'estimated_time'=>'Estimated Time',
                                         'start_date'=>'Start Date',
                                         'due_date'=>'Due Date',                                         
                                         'created_by'=>'Created By',                                         
                                         ));
            
    
    unset($this->widgetSchema['discussion_id']);
    unset($this->widgetSchema['tickets_id']);
    unset($this->widgetSchema['work_hours']);    
    unset($this->validatorSchema['discussion_id']);
    unset($this->validatorSchema['tickets_id']);
    unset($this->validatorSchema['work_hours']);
            
  }
}
