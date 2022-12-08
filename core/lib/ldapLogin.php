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

class ldapLogin
{
  public $config;
  
  function ldapLogin()
  {
    $this->config = array();
    $this->config['ldap_server']      = sfConfig::get('app_ldap_server');;
    $this->config['ldap_port']        = sfConfig::get('app_ldap_port');;
    $this->config['ldap_base_dn']     = sfConfig::get('app_ldap_base_dn');;
    $this->config['ldap_uid']         = sfConfig::get('app_ldap_uid');;
    $this->config['ldap_user_filter'] = sfConfig::get('app_ldap_user_filter');;
    $this->config['ldap_email']       = sfConfig::get('app_ldap_email');;
    $this->config['ldap_user']        = sfConfig::get('app_ldap_user');;
    $this->config['ldap_password']    = sfConfig::get('app_ldap_password');;
  }
  
  function doLdapLogin($username, $password)
  {  	
  	if (!@extension_loaded('ldap'))
  	{
  		return array('status'=>false, 'msg'=>'LDAP extension not available');
  	}
        	
  	if (strlen($this->config['ldap_port'])>0)
  	{
  		$ldap = @ldap_connect($this->config['ldap_server'], $this->config['ldap_port']);
  		
  	}
  	else
  	{
  		$ldap = @ldap_connect($this->config['ldap_server']);
  	}
  
  	if (!$ldap)  	
  	{
  	  return array('status'=>false, 'msg'=>'Could not connect to LDAP server');  		
  	}
  	
  	  	  
  	@ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
  	@ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
  
  	if (strlen($this->config['ldap_user'])>0 || strlen($this->config['ldap_password'])>0)
  	{
  		if (!@ldap_bind($ldap, htmlspecialchars_decode($this->config['ldap_user']), htmlspecialchars_decode($this->config['ldap_password'])))
  		{  			  			
  			return array('status'=>false, 'msg'=>'Binding to LDAP server failed with specified user/password.');
  		}
  	}      
  
  	// ldap_connect only checks whether the specified server is valid, so the connection might still fail
  	$search = @ldap_search(
  		$ldap,
  		htmlspecialchars_decode($this->config['ldap_base_dn']),
  		$this->ldap_user_filter($username),
  		(strlen($this->config['ldap_email'])==0) ?
  			array('cn',htmlspecialchars_decode($this->config['ldap_uid'])) :
  			array('cn',htmlspecialchars_decode($this->config['ldap_uid']), htmlspecialchars_decode($this->config['ldap_email'])),
  		0,
  		1
  	);
  	  	    
  	$ldap_result = @ldap_get_entries($ldap, $search);
  	  
  	if (is_array($ldap_result) && sizeof($ldap_result) > 1)
  	{
  		if (@ldap_bind($ldap, $ldap_result[0]['dn'], htmlspecialchars_decode($password)))
  		{
  			@ldap_close($ldap);  			  			  			
  			
        $userName = '';
        $userEmail = '';
        
        if(isset($ldap_result[0][htmlspecialchars_decode($this->config['ldap_email'])][0]))
        {
          $userEmail = $ldap_result[0][htmlspecialchars_decode($this->config['ldap_email'])][0];
        }
        
        if(isset($ldap_result[0]['cn'][0]))
        {
          $userName = $ldap_result[0]['cn'][0];
        }
  		  			
  			return array('status'=>true, 'name'=>$userName,'email'=>$userEmail);
  		}
  		else
  		{
  			unset($ldap_result);
  			@ldap_close($ldap);
  
  			// Give status about wrong password...
  			return array('status'=>false, 'msg'=>'You have specified an incorrect password. Please check your password and try again. ');
  		}
  	}
  
  	@ldap_close($ldap);
  
  	return array('status'=>false, 'msg'=>'You have specified an incorrect username. Please check your username and try again.');
  }
  
  
  
  /**
  * Generates a filter string for ldap_search to find a user
  *
  * @param	$username	string	Username identifying the searched user
  *
  * @return				string	A filter string for ldap_search
  */
  function ldap_user_filter($username)
  {  	
  	$filter = '(' . $this->config['ldap_uid'] . '=' . $this->ldap_escape(htmlspecialchars_decode($username)) . ')';
  	if ($this->config['ldap_user_filter'])
  	{
  		$_filter = ($this->config['ldap_user_filter'][0] == '(' && substr($this->config['ldap_user_filter'], -1) == ')') ? $this->config['ldap_user_filter'] : "({$this->config['ldap_user_filter']})";
  		$filter = "(&{$filter}{$_filter})";
  	}
  	return $filter;
  }
  
  /**
  * Escapes an LDAP AttributeValue
  */
  function ldap_escape($string)
  {
  	return str_replace(array('*', '\\', '(', ')'), array('\\*', '\\\\', '\\(', '\\)'), $string);
  }

}


