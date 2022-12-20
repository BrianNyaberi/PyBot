<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * ProjectsComments form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjectsCommentsForm extends BaseProjectsCommentsForm
{
  public function configure()
  {
    $projects = $this->getOption('projects');
                      
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor-auto-focus'));
    
    $this->widgetSchema['projects_id'] = new sfWidgetFormInputHidden();    
    $this->setDefault('projects_id', $projects->getId());
    
    $this->widgetSchema['created_by'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();
    $this->setDefault('created_at', date('Y-m-d H:i:s'));
    
    $this->widgetSchema->setLabels(array('projects_status_id'=>'Status',
                                         'description'=>'Comment',                                         
                                         'projects_types_id'=>'Type',
                                         ));        
  }
}
