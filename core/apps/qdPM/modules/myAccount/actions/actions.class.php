<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * myAccount actions.
 *
 * @package    sf_sandbox
 * @subpackage myAccount
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class myAccountActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    app::setPageTitle('My Account',$this->getResponse());
    
    $this->forward404Unless($users = Doctrine_Core::getTable('Users')->find($this->getUser()->getAttribute('id')), sprintf('Object users does not exist (%s).', $this->getUser()->getAttribute('id')));
    $this->form = new UsersForm($users,array('sf_user'=>$this->getUser())); 
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($users = Doctrine_Core::getTable('Users')->find($this->getUser()->getAttribute('id')), sprintf('Object users does not exist (%s).', $this->getUser()->getAttribute('id')));
    $this->form = new UsersForm($users,array('sf_user'=>$this->getUser()));

    $this->processForm($request, $this->form);

    $this->setTemplate('index');
  }
  
  public function executeCheckUser(sfWebRequest $request)
  {
    $q = Doctrine_Core::getTable('Users')->createQuery()->addWhere('email=?',$request->getParameter('email'));        
    
    $q->addWhere('id != ?',$this->getUser()->getAttribute('id'));      
        
    echo $q->count();
    
    exit();      
  }
  
  protected function checkUser($email,$id)
  {
    $q = Doctrine_Core::getTable('Users')->createQuery()->addWhere('email=?',$email);

    $q->addWhere('id != ?',$id);      
     
    if($q->count()>0)
    {
      $this->getUser()->setFlash('userNotices', array('text'=>t::__('Email already exists'),'type'=>'error'));
      $this->redirect('myAccount/index');
    }              
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $files = $request->getFiles();
    $userPhoto = $files['users']['photo']['name'];
    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $user = $this->getUser()->getAttribute('user');
      
      $this->checkUser($form['email']->getValue(),$user->getId());
      
      $form->setFieldValue('users_group_id',$user->getUsersGroupId());
      $form->setFieldValue('active',$user->getActive());
      
      $hasher = new PasswordHash(11, false);
      
      if(isset($form['new_password']))
      {
        if(strlen($form['new_password']->getValue())>0)
        {
          $form->setFieldValue('password', $hasher->HashPassword($form['new_password']->getValue()));
        }
      }

      if(strlen($userPhoto)>0)
      {
        if(app::is_image($files['users']['photo']['tmp_name']))
        {
          $pathinfo = pathinfo($userPhoto);
          $userPhoto =  'app_user_' . time() . '.' . $pathinfo['extension'];
          $filename = sfConfig::get('sf_upload_dir') . '/users/' . $userPhoto;
          move_uploaded_file($files['users']['photo']['tmp_name'], $filename);
          $form->setFieldValue('photo', $userPhoto);
          
          app::image_resize($filename,$filename);
        } 
        else
        {
            $form->setFieldValue('photo','');
        }
      }
      else
      {
        if(app::is_image(sfConfig::get('sf_upload_dir') . '/users/' . $form['photo_preview']->getValue()))
        {
          $form->setFieldValue('photo', $form['photo_preview']->getValue());
        }
        else
        {
          $form->setFieldValue('photo','');
        }
      }
      
      if($form['remove_photo']->getValue()==1 && strlen($form['photo_preview']->getValue())>0)
      {
        if(app::is_image(sfConfig::get('sf_upload_dir') . '/users/' . $form['photo_preview']->getValue()))
        {
          unlink(sfConfig::get('sf_upload_dir') . '/users/' . $form['photo_preview']->getValue());
          $form->setFieldValue('photo','');
        }
      }
      
      $form->protectFieldsValue();
    
      $users = $form->save();
      
      $this->getUser()->setAttribute('user', $users);
      $this->getUser()->setCulture($users->getCulture());
      
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$users->getId(),'users',$this->getUser(),$request);
      
      $this->getUser()->setFlash('userNotices', t::__('Account Updated'));
      $this->redirect('myAccount/index');
    }
  }
}
