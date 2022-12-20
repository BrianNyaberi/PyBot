<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * Users form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UsersForm extends BaseUsersForm
{
  public function configure()
  {        
    $this->widgetSchema['users_group_id'] = new sfWidgetFormChoice(array('choices'=>UsersGroups::getChoicesByType()),array('onChange'=>'set_extra_fields_per_group(this.value)','class'=>'form-control input-medium'));
      
    $this->widgetSchema['active'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('active', 1);
            
    $this->widgetSchema['notify'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('notify', 1);
    $this->setValidator('notify',new sfValidatorString(array('required' => false)));
    
    $this->widgetSchema['remove_photo'] = new sfWidgetFormInputCheckbox(array(),array('value'=>1));
    $this->setDefault('remove_photo', false);
    $this->setValidator('remove_photo',new sfValidatorString(array('required' => false)));
          
    $this->widgetSchema['photo'] = new sfWidgetFormInputFile();
    $this->widgetSchema['photo_preview'] = new sfWidgetFormInputHidden();
    $this->setValidator('photo_preview',new sfValidatorString(array('required' => false)));
    
    $this->widgetSchema['name']->setAttributes(array('class'=>'form-control input-large required','autocomplete'=>'off'));
    $this->widgetSchema['email']->setAttributes(array('class'=>'form-control input-large required','autocomplete'=>'off'));
    
    if($this->getObject()->isNew())
    {
      $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
      $this->widgetSchema['password']->setAttributes(array('class'=>'form-control input-large required','autocomplete'=>'off'));            
    }
    else
    {
      unset($this->widgetSchema['password']);
      unset($this->validatorSchema['password']);
      
      $this->widgetSchema['new_password'] = new sfWidgetFormInputPassword();
      $this->setValidator('new_password',new sfValidatorString(array('required' => false)));
      $this->widgetSchema['new_password']->setAttributes(array('class'=>'form-control input-large','autocomplete'=>'off'));
      
      $this->setDefault('photo_preview',$this->getObject()->getPhoto());
    }
    
    
        
    $this->widgetSchema['culture'] = new sfWidgetFormI18nChoiceLanguage(array('languages'=>app::getLanguageCodes()),array('class'=>'form-control input-medium'));    
    $this->setDefault('culture',sfConfig::get('sf_default_culture'));
         
    
    $this->widgetSchema->setLabels(array('users_group_id'=>'Group',
                                         'name'=>'Full Name',
                                         'remove_photo'=>'Remove Photo',
                                         'new_password'=>'New Password',
                                         'culture'=>'Language',
                                         'default_home_page'=>'Start Page',
                                         'active'=>'Active?',
                                         'notify'=>'send login details to user'));
                                         
    unset($this->widgetSchema['skin']);
    unset($this->validatorSchema['skin']);
    
  }
}
