<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * TasksComments form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TasksCommentsForm extends BaseTasksCommentsForm
{
  public function configure()
  {
    $tasks = $this->getOption('tasks');
    
    if($this->getObject()->isNew())
    {
      $this->widgetSchema['tasks_priority_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksPriority',true)),array('class'=>'form-control input-large'));        
      $this->widgetSchema['tasks_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('TasksStatus',true)),array('class'=>'form-control input-large'));                    
      $this->widgetSchema['due_date'] = new sfWidgetFormInput(array(),array('class'=>'form-control datepicker'));
    }
    else
    {
      $this->widgetSchema['tasks_priority_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['tasks_status_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['due_date'] = new sfWidgetFormInputHidden();
    }
    
            
    $this->widgetSchema['created_by'] = new sfWidgetFormInputHidden();
    
    if(sfConfig::get('app_allow_adit_tasks_comments_date')=='off')
    {
      $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();
    }
    
    $this->setDefault('created_at', date('Y-m-d H:i:s'));        
    
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor-auto-focus'));
    $this->widgetSchema['worked_hours']->setAttributes(array('class'=>'form-control input-small'));    
    
    $this->widgetSchema['tasks_id'] = new sfWidgetFormInputHidden();    
    $this->setDefault('tasks_id', $tasks->getId());
    
    $this->widgetSchema->setLabels(array('tasks_priority_id'=>'Priority',
                                         'tasks_types_id'=>'Type',
                                         'tasks_labels_id'=>'Label',                                         
                                         'tasks_status_id'=>'Status',
                                         'worked_hours'=>'Work Hours',                                         
                                         'due_date'=>'Due Date',
                                         'created_at'=>'Created At',
                                         ));
    

  }
}
