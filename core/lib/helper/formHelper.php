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

function link_to_modalbox($title, $url,$params = '')
{
  return '<a href="#" onClick="openModalBox(\'' . url_for($url) . '\')" ' . $params . '>' . $title . '</a>'; 
}

function link_to_mmodalbox($title, $url,$params = '')
{
  return '<a href="#" onClick="openMultipleActionModalBox(\'' . url_for($url) . '\')" ' . $params . '>' . $title . '</a>'; 
}

function button_tag_modalbox($value='',$url,$attributes = array())
{
  $f = new sfWidgetFormInput();
  return $f->render('',$value,array_merge($attributes,array('type'=>'button','class'=>'btn btn-default','onClick'=>'openModalBox(\'' . url_for($url) . '\')')));
}

function button_tag_mmodalbox($value='',$url,$attributes = array())
{
  $f = new sfWidgetFormInput();
  return $f->render('',$value,array_merge($attributes,array('type'=>'button','class'=>'btn btn-default','onClick'=>'openMultipleActionModalBox(\'' . url_for($url) . '\')')));
}

function input_tag($name='',$value='',$attributes=array())
{
  $f = new sfWidgetFormInput();
  return $f->render($name,$value,$attributes);
}

function input_file_tag($name='',$value='',$attributes=array())
{
  $attributes = array_merge($attributes,array('type'=>'file'));
  
  return input_tag($name,$value,$attributes);
}

function input_checkbox_tag($name='',$value='',$attributes=array())
{
  $attributes = array_merge($attributes,array('type'=>'checkbox'));
    
  return input_tag($name,$value,$attributes);
}

function textarea_tag($name='',$value='',$attributes=array())
{
  $f = new sfWidgetFormTextarea();
  return $f->render($name,$value,$attributes);
}

function button_tag($value='',$attributes = array())
{
  $f = new sfWidgetFormInput();
  return $f->render('',$value,array_merge($attributes,array('type'=>'button','class'=>'btn')));
}

function button_to_tag($value='',$url,$attributes = array())
{
  $f = new sfWidgetFormInput();
  return $f->render('',$value,array_merge($attributes,array('type'=>'button','class'=>'btn btn-default','onClick'=>'location.href="' . url_for($url) . '"')));
}

function submit_tag($value='',$attributes = array())
{
  $f = new sfWidgetFormInput();
  return $f->render('',$value,array_merge($attributes,array('type'=>'submit','class'=>'btn btn-primary')));
}

function input_hidden_tag($name='',$value='',$attributes = array())
{
  $f = new sfWidgetFormInput();
  return $f->render($name,$value,array_merge($attributes,array('type'=>'hidden')));
}

function select_tag($name='',$value=array(),$options, $attributes=array())
{  
  $f = new sfWidgetFormChoice($options);
  return $f->render($name,$value,$attributes);
}

function languages_select_tag($name,$value,$attributes=array())
{
  $f = new sfWidgetFormI18nChoiceLanguage(array('languages'=>languagesController::getLanguageCodes()));
  return $f->render($name,$value,$attributes);
}


