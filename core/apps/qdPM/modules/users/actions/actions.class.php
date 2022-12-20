<?php
/**
* WORK SMART
*/
?>
<?php

/**
 * users actions.
 *
 * @package    sf_sandbox
 * @subpackage users
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class usersActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    
    if(!$this->getUser()->hasAttribute('users_filter'))
    {
      $this->getUser()->setAttribute('users_filter', array());
    }
                     
    $this->filter_by = $this->getUser()->getAttribute('users_filter');
    
    if($fb = $request->getParameter('filter_by'))
    {
      $this->filter_by[key($fb)]=current($fb);
      $this->getUser()->setAttribute('users_filter', $this->filter_by);
      
      $this->redirect('users/index');
    }
    
    if($request->hasParameter('remove_filter'))
    {
      unset($this->filter_by[$request->getParameter('remove_filter')]);    
      $this->getUser()->setAttribute('users_filter', $this->filter_by);
      
      $this->redirect('users/index');
    }
  
    $q = Doctrine_Core::getTable('Users')
      ->createQuery('u')->leftJoin('u.UsersGroups ug');
      
    if($request->hasParameter('search'))
    {    
      $q = app::addSearchQuery($q, $request->getParameter('search'),'UsersComments','u',$request->getParameter('search_by_extrafields'));      
    }
    else
    {
      $q = Users::addFiltersToQuery($q,$this->getUser()->getAttribute('users_filter'));                  
    }  
      
    $this->userss =   $q->orderBy('ug.name, u.name')->execute();
      
    app::setPageTitle('Users',$this->getResponse());
        
  }
  
  public function executeInfo(sfWebRequest $request)
  {
    $this->users = Doctrine_Core::getTable('Users')->find($request->getParameter('id'));
  }
  
  public function executeCheckUser(sfWebRequest $request)
  {
    if($request->getParameter('email')==sfConfig::get('app_administrator_email'))
    {
      echo 1;
      exit();
    }
      
    $q = Doctrine_Core::getTable('Users')->createQuery()->addWhere('email=?',$request->getParameter('email'));
    
    if($request->hasParameter('id'))
    {
      $q->addWhere('id != ?',$request->getParameter('id'));      
    }
    
    echo $q->count();
    
    exit();      
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new UsersForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new UsersForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($users = Doctrine_Core::getTable('Users')->find(array($request->getParameter('id'))), sprintf('Object users does not exist (%s).', $request->getParameter('id')));
    $this->form = new UsersForm($users);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($users = Doctrine_Core::getTable('Users')->find(array($request->getParameter('id'))), sprintf('Object users does not exist (%s).', $request->getParameter('id')));
    $this->form = new UsersForm($users);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($users = Doctrine_Core::getTable('Users')->find(array($request->getParameter('id'))), sprintf('Object users does not exist (%s).', $request->getParameter('id')));
    
    if($users->getId()==$this->getUser()->getAttribute('id'))
    {
      $this->getUser()->setFlash('userNotices', array('type'=>'warning','text'=>t::__('You can\'t delete yourself.')));
    }
    elseif(Users::countRelatedItemsByUsersId($users->getId())==0)
    {
      ExtraFieldsList::deleteFieldsByBindId($users->getId(),'users');
      
      $users->delete();
      
      $this->getUser()->setFlash('userNotices', t::__('User Deleted'));
    }
    else
    {
      $users->setActive(0);
      $users->save();
      
      $this->getUser()->setFlash('userNotices', array('type'=>'warning','text'=>t::__("User can't be  Deleted because it has related items. Currently user status set to Inactive and user can't login to the system.")));
      
    }

    $this->redirect('users/index');
  }
  
  protected function checkUser($email,$id)
  {
    if($email==sfConfig::get('app_administrator_email'))
    {
      $this->getUser()->setFlash('userNotices', array('text'=>t::__('Email already exists'),'type'=>'error'));
      $this->redirect('users/index');
    }
  
    $q = Doctrine_Core::getTable('Users')->createQuery()->addWhere('email=?',$email);
    
    if($id>0)
    {
      $q->addWhere('id != ?',$id);      
    }
    
    if($q->count()>0)
    {
      $this->getUser()->setFlash('userNotices', array('text'=>t::__('Email already exists'),'type'=>'error'));
      $this->redirect('users/index');
    }              
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $files = $request->getFiles();
    $userPhoto = $files['users']['photo']['name'];
            
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $this->checkUser($form['email']->getValue(),$request->getParameter('id'));
      
      
      $hasher = new PasswordHash(11, false);
      
      if(isset($form['password']))
      {
        $form->setFieldValue('password', $hasher->HashPassword($form['password']->getValue()));
      }
      
      if(isset($form['new_password']))
      {
        if(strlen($form['new_password']->getValue())>0)
        {
          $form->setFieldValue('password', $hasher->HashPassword($form['new_password']->getValue()));
        }
      }

      if(strlen($userPhoto)>0)
      {
        $userPhoto =  rand(111111,999999) . '-' . $userPhoto;
        $filename = sfConfig::get('sf_upload_dir') . '/users/' . $userPhoto;
        
        if(getimagesize($files['users']['photo']['tmp_name']))
        {
          move_uploaded_file($files['users']['photo']['tmp_name'], $filename);
          $form->setFieldValue('photo', $userPhoto);

          app::image_resize($filename,$filename);
        }
        else
        {
          $form->setFieldValue('photo', $form['photo_preview']->getValue());
        }
      }
      else
      {
        $form->setFieldValue('photo', $form['photo_preview']->getValue());
      }
      
      if($form['remove_photo']->getValue()==1 && strlen($form['photo_preview']->getValue())>0)
      {
        unlink(sfConfig::get('sf_upload_dir') . '/users/' . $form['photo_preview']->getValue());
        $form->setFieldValue('photo','');
      }
    
      $form->protectFieldsValue();
      
      $users = $form->save();
      
      ExtraFieldsList::setValues($request->getParameter('extra_fields'),$users->getId(),'users',$this->getUser(),$request);
      
      if($form['notify']->getValue()==1)
      {
        $this->notifyUser($users,$form['password']->getValue());
      }
      
      $this->redirect('users/index');
    }
  }
  
  protected function notifyUser($user,$password)
  {
    if(strlen(sfConfig::get('app_new_user_email_subject'))>0)
    {
      $subject = sfConfig::get('app_new_user_email_subject');
    }
    else
    {
      $subject = t::__('Your account has been created in') . ' ' . sfConfig::get('app_app_name');
    }
    
    $login_details = '<p><b>' . t::__('Login Details') . ':</b></p><p>' . t::__('Email') . ': ' . $user->getEmail() . '<br>' . t::__('Password') . ': ' . $password . '</p><p><a href="' . app::public_url('login/index') . '">' . app::public_url('login/index') . '</a></p>';
    
    if(strlen(sfConfig::get('app_new_user_email_body'))>0)
    {
      $body = sfConfig::get('app_new_user_email_body');
      
      $body = str_replace('[user_name]',$user->getName(),$body);
      
      if(strstr($body,'[login_details]'))
      {
        $body = str_replace('[login_details]',$login_details,$body);
      }
      else
      {
        $body .= $login_details;
      }
    }
    else
    {
      $body = $login_details;
    }          
                              
    $from = array(sfConfig::get('app_administrator_email')=>sfConfig::get('app_app_name'));
    $to = array($user->getEmail()=>$user->getName());
    
    $template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateOneColumn.html');
              
    $body = str_replace('[COLUMN1]',$body,$template);
                  
    Users::sendEmail($from, $to, $subject, $body, $this->getUser());
  }
  
  public function executeExport(sfWebRequest $request)
  {
    
    $this->columns = array('id'=>t::__('Id'),                                                                                 
                           'UsersGroups'=>t::__('Group'),
                           'name'=>t::__('Name'),
                           'email'=>t::__('Email'),                                                                                 
                           );
                           
    $extra_fields = ExtraFieldsList::getFieldsByType('users',$this->getUser(),false,array('all'=>true));
    
    foreach($extra_fields as $v)
    {
      $this->columns['extra_field_' . $v['id']]=$v['name'];
    }                           
        
    
    if($fields = $request->getParameter('fields'))
    {
      $separator = "\t";
      $format = $request->getParameter('format','.csv');
      $filename = $request->getParameter('filename','users');
			
			header("Content-type: Application/octet-stream");      
			header("Content-disposition: attachment; filename=" . $filename . "." . $format);
			header("Pragma: no-cache");
			header("Expires: 0");
    
      $content = '';
      foreach($fields as $f)
      {
        $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$this->columns[$f]) . $separator;
      }
      $content .= "\n";
      
      if($format=='csv')
      {
        echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
      }
      else
      {
        echo $content;
      }
    
      if(strlen($request->getParameter('selected_items')==0)) exit();
      
       $q = Doctrine_Core::getTable('Users')
          ->createQuery('u')->leftJoin('u.UsersGroups ug')
          ->whereIn('u.id',explode(',',$request->getParameter('selected_items')));   
          
      $users = $q->orderBy('ug.name, u.name')->fetchArray();
          
      foreach($users as $u)
      {
        $ex_values = ExtraFieldsList::getValuesList($extra_fields,$u['id']);
        
        $content = '';
        
        foreach($fields as $f)
        {
          $v = '';
          
          if(in_array($f,array('id','name','email')))
          {            
            $v=$u[$f];
          }
          elseif(strstr($f,'extra_field_'))
          {
            if($ex = Doctrine_Core::getTable('ExtraFields')->find(str_replace('extra_field_','',$f)))
            {
              $v = ExtraFieldsList::renderFieldValueByType($ex,$ex_values,array(),true);
              $v = str_replace('<br>',', ',$v);
            }
          }          
          else
          {            
            $v=app::getArrayName($u,$f);
          }
          
          $content .= str_replace(array("\n\r","\r","\n",$separator),' ',$v) . $separator;
        }
        
        $content .= "\n";
      
        if($format=='csv')
        {
          echo chr( 0xFF ) . chr( 0xFE ) . mb_convert_encoding( $content, 'UTF-16LE', 'UTF-8' );
        }
        else
        {
          echo $content;
        }        
      }
            
      exit(); 
    }
  }  
  
  
  public function executeSendEmail(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getAttribute('user');
    
    if($request->isMethod(sfRequest::PUT))
    {
      if($request->hasParameter('users_groups'))
      {
         foreach($request->getParameter('users_groups') as $id)
         {
            $choices = Users::getEmailChoicesByGroupId($id);
            
            foreach($choices as $email=>$name)
            {
              $from = array($this->user->getEmail()=>$this->user->getName());
              $to = array($email=>$name);
              $subject = $request->getParameter('subject');
              $body = $request->getParameter('message');
              
              $template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateOneColumn.html');
              
              $body = str_replace('[COLUMN1]',$body,$template);
              
              Users::sendEmail($from, $to, $subject, $body, $this->getUser(),false);
            }
         } 
         
         $this->getUser()->setFlash('userNotices',t::__('Message Sent'));
         $this->redirect('users/sendEmail');       
      }
      else
      {
         $this->getUser()->setFlash('userNotices', array('text'=>t::__('User Group is not selected'),'type'=>'error'));
      }
    }
    
    app::setPageTitle('Send email to active users',$this->getResponse());
  }
 
}
