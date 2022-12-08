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

class cfgListingController
{
  public $module;
  
  public function __construct($module,$url_params = '')
	{
	 $this->module = $module;
	 $this->url_params = $url_params;
	}
	
	public function add_url_params($d)
	{
    if(strlen($this->url_params)>0)
    {
      return $d . $this->url_params; 
    }
    else
    {
      return '';
    }    
  }
	
	public function insert_button($title = false)
	{
	  if(!$title)
	  {
      $title = __('Add');
    }
    
    //
	  //$f = new sfWidgetFormInput();
	  //$attributes = array('type'=>'button', 'class'=>'btn btn-primary','onClick'=>'openModalBox(\'' . url_for($this->module . '/new' . $this->add_url_params('?'),true) . '\')');
    //return $f->render('',$title,$attributes);
    return '<button class="btn btn-primary" onClick="openModalBox(\'' . url_for($this->module . '/new' . $this->add_url_params('?'),true) . '\')">' . $title . '</button>';
  }
  
  public function sort_button()
	{
	  //$f = new sfWidgetFormInput();
	  //$attributes = array('type'=>'button', 'class'=>'btn btn-default','onClick'=>'openModalBox(\'' . url_for('app/sortItems?t=' . $this->module . $this->add_url_params('&') ,true) . '\')');
    //return $f->render('',__('Sort'),$attributes);
    
    return '<button class="btn btn-default" onClick="openModalBox(\'' . url_for('app/sortItems?t=' . $this->module . $this->add_url_params('&') ,true) . '\')">' . __('Sort') . '</button>';
  }
  
  public function edit_button($id)
	{
	  //$f = new sfWidgetFormInput();
	  //$attributes = array('type'=>'image','title'=>__('Edit'),'class'=>'actionIcon iconEdit','src'=>public_path('images/icons/edit.png',true),'onClick'=>'openModalBox(\'' . url_for($this->module . '/edit?id=' . $id . $this->add_url_params('&'),true) . '\')');
    //return $f->render('',__('Edit'),$attributes);
    
    return '<a href="#" class="btn btn-default btn-xs purple" onClick="openModalBox(\'' . url_for($this->module . '/edit?id=' . $id . $this->add_url_params('&'),true) . '\'); return false;"><i class="fa fa-edit"></i></a>';
  }
        
  public function delete_button($id)
	{
    return link_to('<i class="fa fa-trash-o"></i>', $this->module  . '/delete?id='  . $id  . $this->add_url_params('&'), array('method' => 'delete','class'=>'btn btn-default btn-xs purple', 'confirm' => __('Are you sure?')));
    
  }
  
  public function delete_mbutton($id)
	{
    return link_to_modalbox('<i class="fa fa-trash-o"></i>',$this->module  . '/delete?id='  . $id  . $this->add_url_params('&'),'class="btn btn-default btn-xs purple"');
  }
  
  public function action_buttons($id)
  {
    return $this->delete_button($id)  . ' ' .  $this->edit_button($id);
  }
} 
