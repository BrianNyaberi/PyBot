<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * DiscussionsComments form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DiscussionsCommentsForm extends BaseDiscussionsCommentsForm
{
  public function configure()
  {
    $discussions = $this->getOption('discussions');
    
    if($this->getObject()->isNew())
    {            
      $this->widgetSchema['discussions_status_id'] = new sfWidgetFormChoice(array('choices'=>app::getItemsChoicesByTable('DiscussionsStatus',true)),array('class'=>'form-control input-large'));
    }
    else
    {
      $this->widgetSchema['discussions_status_id'] = new sfWidgetFormInputHidden();
    }
    
        
    $this->widgetSchema['users_id'] = new sfWidgetFormInputHidden();
    
    $this->widgetSchema['created_at'] = new sfWidgetFormInputHidden();        
    $this->setDefault('created_at', date('Y-m-d H:i:s'));        
    
    $this->widgetSchema['description']->setAttributes(array('class'=>'form-control editor-auto-focus'));
        
    $this->widgetSchema['discussions_id'] = new sfWidgetFormInputHidden();    
    $this->setDefault('discussions_id', $discussions->getId());
    
    $this->widgetSchema->setLabels(array('discussions_status_id'=>'Status',                                            
                                         ));
        
  }
}
