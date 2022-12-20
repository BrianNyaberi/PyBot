<?php
/**

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


