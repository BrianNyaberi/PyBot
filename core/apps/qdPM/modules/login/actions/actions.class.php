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
 * login actions.
 *
 * @package    sf_sandbox
 * @subpackage login
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class loginActions extends sfActions
{
  public function executeKeepSession()
  {
    exit();
  }
  
  public function executeLogoff()
  {
    $this->getResponse()->setCookie('stay_logged','',time() - 3600,'/');    
    $this->getResponse()->setCookie('remember_pass','',time() - 3600,'/');
  
    $this->getUser()->setAuthenticated(false);
    $this->getUser()->getAttributeHolder()->clear();
    $this->getUser()->clearCredentials();
                            
    $this->redirect('login/index');
  }
  
  public function doUserLogin($user, $request)
  {
    $this->getUser()->setAttribute('id', $user->getId());    
    $this->getUser()->setAttribute('users_group_id', $user->getUsersGroupId());    
    $this->getUser()->setAttribute('user', $user);
    
    $this->getUser()->setAuthenticated(true);
    
    Attachments::clearTmpUploadedFiles($this->getUser());
    
    $this->getUser()->setCulture($user->getCulture());
    
    if(strlen($user->getSkin())>0)
    {
      $this->getResponse()->setCookie('skin', $user->getSkin(), time()+31536000,'','');
    }
    
    $ug = $user->getUsersGroups();
    
    if($ug->getAllowManageProjects()>0){$this->getUser()->addCredential('reports_access_projects');}
    if($ug->getAllowManageTasks()>0)
    {
      $this->getUser()->addCredential('reports_access_tasks'); 
      $this->getUser()->addCredential('reports_access_time'); 
      $this->getUser()->addCredential('reports_access_time_personal');
      $this->getUser()->addCredential('reports_access_gantt');
    }
    
    if($ug->getAllowManageTickets()>0){$this->getUser()->addCredential('reports_access_tickets');}
    if($ug->getAllowManageDiscussions()>0){$this->getUser()->addCredential('reports_access_discussions');}
                        
    if($ug->getAllowManageUsers()==1){$this->getUser()->addCredential('allow_manage_users');}    
    if($ug->getAllowManageConfiguration()==1){$this->getUser()->addCredential('allow_manage_configuration');}
    
    $this->getUser()->addCredential('allow_manage_personal_scheduler');
                                                                                                  
    if(strlen($request->getParameter('http_referer'))>0)
    {
      $this->redirect($request->getParameter('http_referer'));
    }
    else
    {
      $this->redirect('dashboard/index');
    }                
  }
  
  public function executeRestorePassword(sfWebRequest $request)
  {
    $this->form = new RestorePasswordForm();
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {  
        $newRandomPassword = Users::getRandomPassword();

        $hasher = new PasswordHash(11, false);
          
        $is_user = false;    
                  
        if($user = Doctrine_Core::getTable('Users')->createQuery()->addWhere('email=?',$this->form['email']->getValue())->addWhere('active=1')->fetchOne())
        {
          $user->setPassword($hasher->HashPassword($newRandomPassword));
          $user->save();
          
          $to = array($user->getEmail()=>$user->getName());
          
          $is_user = true;
        }
        elseif($this->form['email']->getValue()==sfConfig::get('app_administrator_email') )
        {
          if($cfg = Doctrine_Core::getTable('configuration')->createQuery()->addWhere('key=?','app_administrator_password')->fetchOne())
          {
            $cfg->setValue($hasher->HashPassword($newRandomPassword));
            $cfg->save();
            
            $to = array(sfConfig::get('app_administrator_email')=>'admin');
            
            $is_user = true;
          } 
        }
        
        
        if($is_user)
        {                                                                                          
          $body = t::__('You are receiving this notification because you have requested a new password be sent for your account on') . ' ' . sfConfig::get('app_app_name') . '<br><br>' . t::__('Password') . ': ' . $newRandomPassword;          
                              
          $from = array(sfConfig::get('app_administrator_email')=>sfConfig::get('app_app_name'));
          
          $subject = t::__('New Password on') . ' ' . sfConfig::get('app_app_name');
          
          $template = file_get_contents(sfConfig::get('sf_app_config_dir') . '/emailTemplateOneColumn.html');              
          $body = str_replace('[COLUMN1]',$body,$template);
          
          Users::sendEmail($from, $to, $subject, $body, $this->getUser(),true,false);
                                                  
          $this->getUser()->setFlash('userNotices', t::__('A new password has been sent to your e-mail address'));

          $this->redirect('login/index');
        }
        else
        {
          $this->getUser()->setFlash('userNotices', array('text'=>t::__('No records found'),'type'=>'error'));
          $this->redirect('login/restorePassword');
        }
      }
    }
    
    app::setPageTitle('Restore Password',$this->getResponse());
    
    $this->setLayout('loginLayout');
  }
  
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {      
    $this->form = new LoginForm();
    
    if ($request->isMethod('post'))
    {          
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {      
        $hasher = new PasswordHash(11, false);
        $password = $this->form['password']->getValue();
                                                  
        if($this->form['email']->getValue()==sfConfig::get('app_administrator_email') && $hasher->CheckPassword($password, sfConfig::get('app_administrator_password')))
        {
          $this->getUser()->setAttribute('id', 0);
          $this->getUser()->setAttribute('users_group_id', 0);
          $this->getUser()->setAuthenticated(true);
          $this->getUser()->addCredentials('allow_manage_users', 'allow_manage_configuration');          
          $this->redirect('users/index');
        }
        else
        {
          $user = Doctrine_Core::getTable('Users')
                ->createQuery('u')->leftJoin('u.UsersGroups')
                ->addWhere('active=?',1)
                ->addWhere('email=?',$this->form['email']->getValue())
                ->fetchOne();
                //->addWhere('password=?',md5($this->form['password']->getValue()));
                                             
          $error = false;                     
                              
          if($user)
          {
             if(strlen($user->getPassword())==32 )
             {                                             
               if($user->getPassword()!=md5($password))
               {
                $error = true;
               }               
             }
             elseif(!$hasher->CheckPassword($password, $user->getPassword()))
             {
               $error = true;
             }
          }
          else
          {
            $error = true;
          }     
                  
          if(!$error)
          {
            if($request->getParameter('remember_me')==1)
            {
              $this->getResponse()->setCookie('remember_me', 1, time()+60*60*24*100,'/');
              $this->getResponse()->setCookie('stay_logged', 1, time()+60*60*24*100,'/');
              $this->getResponse()->setCookie('remember_user',  base64_encode($this->form['email']->getValue()), time()+60*60*24*100,'/');
              $this->getResponse()->setCookie('remember_pass', base64_encode(md5($this->form['password']->getValue())), time()+60*60*24*100,'/');          
            }
            else
            {
              $this->getResponse()->setCookie('remember_me','',time() - 3600,'/');
              $this->getResponse()->setCookie('stay_logged','',time() - 3600,'/');
              $this->getResponse()->setCookie('remember_user','',time() - 3600,'/');
              $this->getResponse()->setCookie('remember_pass','',time() - 3600,'/'); 
            } 
        
            $this->doUserLogin($user,$request);          
          }
          else
          {
            $this->getUser()->setFlash('userNotices', array('text'=>t::__('No match for Email and/or Password'),'type'=>'error'));
            $this->redirect('login/index');
          }
        }
      }
    }
    elseif($request->getCookie('stay_logged')==1 and $request->getCookie('remember_me')==1)
    {      
      $q = Doctrine_Core::getTable('Users')
            ->createQuery('u')->leftJoin('u.UsersGroups')
            ->addWhere('active=?',1)
            ->addWhere('email=?',base64_decode($request->getCookie('remember_user')))
            ->addWhere('password=?',base64_decode($request->getCookie('remember_pass')));
              
      if($user = $q->fetchOne())
      {      
      
        if(isset($_SERVER['REQUEST_URI']))
        {
          if(!stristr($_SERVER['REQUEST_URI'],'/login') and !stristr($_SERVER['REQUEST_URI'],'/create') and !stristr($_SERVER['REQUEST_URI'],'/edit') and !stristr($_SERVER['REQUEST_URI'],'/update') and !stristr($_SERVER['REQUEST_URI'],'/new'))
          {
            if(isset($_SERVER['HTTPS']))
            {
              $http_referer = ($_SERVER['HTTPS']=='on' ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }
            else
            {
              $http_referer = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }
            
            $request->setParameter('http_referer',$http_referer);
          }
        }      
        $this->doUserLogin($user,$request);          
      }
    }      

    app::setPageTitle('Login',$this->getResponse());
    
    $this->setLayout('loginLayout');
  }  
  
  public function executeLdap(sfWebRequest $request)
  {
    $this->form = new LdapLoginForm();
    
    if ($request->isMethod('post'))
    { 
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
      
         $ldap = new ldapLogin();
         $user_attr = $ldap->doLdapLogin($this->form['user']->getValue(), $this->form['password']->getValue());
          
          if($user_attr['status']==true)
          {  
            $userName = $this->form['user']->getValue();                    
            $userEmail = $this->form['user']->getValue() . '@localhost.com';
            
            if(strlen($user_attr['email'])>0)
            {
              $userEmail = $user_attr['email']; 
            }
            
            if(strlen($user_attr['name'])>0)
            {
              $userName = $user_attr['name']; 
            }
            

            $q = Doctrine_Core::getTable('Users')->createQuery()->addWhere('email=?',$userEmail);
            
            if(!$user = $q->fetchOne())
            {
              $user = new Users();
              $user->setUsersGroupId(sfConfig::get('app_ldap_default_user_group'));
              $user->setName($userName);
              $user->setEmail($userEmail);
              $user->setPassword(md5($this->form['password']->getValue()));
              $user->setActive(1);
              $user->setCulture(sfConfig::get('sf_default_culture'));            
              $user->save();          
            }
            else
            {
              if($user->getActive()!=1)
              {
                $this->getUser()->setFlash('userNotices', I18NText::__('Your account is not active'));
                $this->redirect('login/ldap');
              }
            }
                      
             $this->doUserLogin($user,$request);                         
          }
          else
          {
            
            $this->getUser()->setFlash('userNotices', t::__($user_attr['msg']));
            $this->redirect('login/ldap');
          }
   
      }
    }
    
    app::setPageTitle('LDAP Login',$this->getResponse());
    
    $this->setLayout('loginLayout');
          
  }
}
