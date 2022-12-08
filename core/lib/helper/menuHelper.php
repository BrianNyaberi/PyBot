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
  function renderDropDownMenu($menu = array(), $html='',$level=0)
  { 
    if($level==0)
    {     
      $html .= '
        <ul class="dropdown-menu">';
    }
      
    foreach($menu as $v)
    {
        
      if(isset($v['is_hr']))
      {
        if($v['is_hr']==true)
        {
          $html .= '<li class="divider"></li>';
        }
      }
      
      
      if(isset($v['modalbox']))
      {
        $url = 'onClick="openModalBox(\'' . url_for($v['url']). '\')" class="cursor-pointer"';
      }
      else
      {
        $url = 'href="' . url_for($v['url']) . '"';
      }
      
      $html .= '
        <li><a ' . $url . '><i class="fa ' . $v['class'] . '"></i> ' . $v['title']  . (isset($v['counts']) ? ' <span class="badge badge-info">' . $v['counts'] . '</span>':''). '</a>';
            
        
      if(isset($v['submenu']))
      {
        $html = renderDropDownMenu($v['submenu'],$html,$level+1);
      }

      
      $html .= '
        </li>' . "\n";  
    }  
      
    $html .= '
      </ul>';
    
    return $html;    
  } 
  
  function isSidebarMenuItemActive($url,$pageData,$level)
  {
    $menu_cfg_users = array('usersGroups','extraFields');
    $menu_cfg_projects = array('projectsStatus','projectsTypes','phases','phasesStatus','versionsStatus');
    $menu_cfg_tasks = array('tasksStatus','tasksTypes','tasksLabels','tasksPriority');
    $menu_cfg_tickets = array('departments','ticketsStatus','ticketsTypes');
    $menu_cfg_discussions = array('discussionsStatus');
    $menu_tools = array('backup','xlsTasksImport');
    $menu_users = array('users','sendEmail');
    $menu_reports = array('projectsReports','userReports','ticketsReports','discussionsReports','timeReport','ganttChart');
            
    $moduleName = substr($url,0,strpos($url,'/'));
    $actionName = substr($url,strpos($url,'/')+1);
    
        
    if(in_array($moduleName,$menu_reports) and $level==0 and in_array($pageData['moduleName'],$menu_reports))
    {      
      return true;
    } 
    if(in_array($moduleName,$menu_reports) and $level==1 and in_array($pageData['moduleName'],$menu_reports) and $pageData['moduleName']==$moduleName)
    {      
      return true;
    }                        
    elseif($pageData['sf_request']->getParameter('type')=='tasks_columns_list' and $moduleName=='tasksStatus' and $level==1)
    {
      return true;
    }    
    elseif($pageData['sf_request']->hasParameter('type') and $moduleName=='configuration')
    {               
      if($pageData['sf_request']->getParameter('type')=='tasks_columns_list' and $level==1) return false;
      
      if(strstr($url,'type'))
      {
        if(strstr($url,'type=' . $pageData['sf_request']->getParameter('type')))
        {
          return true;
        }        
      }
      else
      {                
        return true;
      }
    }
    elseif($moduleName=='configuration' and in_array($pageData['moduleName'],array_merge($menu_cfg_users,$menu_cfg_projects,$menu_cfg_tasks,$menu_cfg_tickets,$menu_cfg_discussions)) and $level==0)
    {
      return true;
    }
    elseif(in_array($moduleName,$menu_cfg_projects) and $level==1 and in_array($pageData['moduleName'],$menu_cfg_projects))
    {      
      return true;
    }
    elseif(in_array($moduleName,$menu_cfg_tasks) and $level==1 and in_array($pageData['moduleName'],$menu_cfg_tasks))
    {      
      return true;
    }
    elseif(in_array($moduleName,$menu_cfg_tickets) and $level==1 and in_array($pageData['moduleName'],$menu_cfg_tickets))
    {      
      return true;
    }
    elseif(in_array($moduleName,$menu_cfg_discussions) and $level==1 and in_array($pageData['moduleName'],$menu_cfg_discussions))
    {      
      return true;
    }
    elseif($pageData['sf_request']->getParameter('bind_type')=='users' and $moduleName=='usersGroups' and $level==1)
    {
      return true;
    }  
    elseif($pageData['sf_request']->getParameter('bind_type')=='projects' and $moduleName=='projectsStatus' and $level==1)
    {
      return true;
    }
    elseif($pageData['sf_request']->getParameter('bind_type')=='tasks' and $moduleName=='tasksStatus' and $level==1)
    {
      return true;
    }
    elseif($pageData['sf_request']->getParameter('bind_type')=='tickets' and $moduleName=='departments' and $level==1)
    {
      return true;
    }
    elseif($pageData['sf_request']->getParameter('bind_type')=='discussions' and $moduleName=='discussionsStatus' and $level==1)
    {
      return true;
    }
    elseif($pageData['sf_request']->hasParameter('bind_type') and $moduleName=='extraFields')
    {      
      if(strstr($url,'bind_type=' . $pageData['sf_request']->getParameter('bind_type')))
      {
        return true;
      }          
    }    
    elseif(in_array($actionName,$menu_tools) and $level==0 and in_array($pageData['actionName'],$menu_tools))
    {      
      return true;
    }
    elseif(in_array($moduleName,$menu_users) and $level==0 and in_array($pageData['actionName'],$menu_users))
    {      
      return true;
    }    
    elseif($pageData['moduleName']==$moduleName and $pageData['actionName']==$actionName)
    {          
      return true;
    }
    
    
        
  }
  
  function renderSidebarMenu($menu = array(), $html='',$level=0,$currentPageData)
  { 
    if($level>0)
    {     
      $html .= '
        <ul class="sub-menu">';
    }
      
    foreach($menu as $v)
    {
        
      if(isset($v['is_hr']))
      {
        if($v['is_hr']==true)
        {
          $html .= '<li class="divider"></li>';
        }
      }
      
      $is_active = isSidebarMenuItemActive($v['url'],$currentPageData,$level);
      
      if(strlen($html)==0)
      {
        $html .= '<li class="start  ' .($is_active ? 'active':'') . '">';
      }
      else
      {
        $html .= '<li ' .  ($is_active ? 'class="active"':'') . ' >';
      }
      
      
      $url = '';
      
      if(isset($v['url']))
      {
        if(isset($v['modalbox']))
        {
          $url = 'onClick="openModalBox(\'' . url_for($v['url']). '\')" class="cursor-pointer"';
        }
        elseif(isset($v['mamodalbox']))
        {
          $url = 'onClick="openMultipleActionModalBox(\'' . url_for($v['url']). '\')" class="cursor-pointer"';
        }
        else
        {
          $url = 'href="' . url_for($v['url']) . '"';          
        }
      }
      elseif(isset($v['onClick']))
      {
        $url = 'onClick="' . $v['onClick'] . '" class="cursor-pointer"';
      }
      
      if(!isset($v['target'])) $v['target'] = false;
      
      $html .= '
        <a ' . ($v['target'] ? 'target="' . $v['target'] . '"':''). ' ' . $url . '>' . (isset($v['class']) ? '<i class="fa ' . $v['class'] . '"></i> ':'') . '<span class="title">' . $v['title'] . '</span>' . (isset($v['submenu']) ? '<span class="arrow ' . ($is_active ? 'open':'') . '"></span>':''). '</a>';
            
        
      if(isset($v['submenu']))
      {
        $html = renderSidebarMenu($v['submenu'],$html,$level+1,$currentPageData);
      }

      
      $html .= '
        </li>' . "\n";  
    }  
    
    if($level>0)
    {  
      $html .= '
        </ul>';
    }
    
    return $html;    
  }   


  
  function renderMenu($menu = array(),$class='menu', $id='navigationMenu',$html='',$level=0)
  { 
    if($level==0)
    {     
      $html .= '
        <ul id="' . $id . '">';
    }
    elseif($level==1)
    {
      $html .= '
        <ul class="' . $id . ' first">';
    }
    else
    {
      $html .= '
        <ul class="' . $id . ' level' . $level . '">';
    }
      
      for($i=0; $i<sizeof($menu); $i++)
      {
      
        $url = '';
        
        if(isset($menu[$i]['url']))
        {
          if(isset($menu[$i]['modalbox']))
          {
            $url = 'onClick="openModalBox(\'' . url_for($menu[$i]['url'],true). '\')"';
          }
          elseif(isset($menu[$i]['mamodalbox']))
          {
            $url = 'onClick="openMultipleActionModalBox(\'' . url_for($menu[$i]['url'],true). '\')"';
          }
          else
          {
            $url = 'onClick="location.href=\'' . url_for($menu[$i]['url'],true). '\'"';
            $menu[$i]['title'] = '<a href="' . url_for($menu[$i]['url'],true) . '">' . $menu[$i]['title'] . '</a>';
          }
        }
        elseif(isset($menu[$i]['onClick']))
        {
          $url = 'onClick="' . $menu[$i]['onClick'] . '"';
        }
      
        $html .= '
          <li>';
        
        if($i==0 and $level>0)
        {
          $html .= '
              <div class="' . $class . 'SubHeader"></div>
          ';
        }  
        
        $menu_arrow_calss = '';
        
        if(isset($menu[$i]['submenu']) and $level == 0)
        {
          if(count($menu[$i]['submenu'])>0)
          {
            $menu_arrow_calss = 'class="menuArrowBottom"';
          }
        }
        
        if(isset($menu[$i]['submenu']) and $level>0)
        {
          if(count($menu[$i]['submenu'])>0)
          {
            $menu_arrow_calss = 'class="menuArrowRight"';
          }
        }
        
        if(!isset($menu[$i]['is_selected'])) $menu[$i]['is_selected'] = false;
        if(!isset($menu[$i]['is_hr'])) $menu[$i]['is_hr'] = false;
          
        $html .= '
              <div class="' . $class . ($level>0?'Sub':'')  . ($menu[$i]['is_selected']?' selected': '')  . ($menu[$i]['is_hr']?' hr': '') . '"><div ' . $menu_arrow_calss .  ' ' . $url . '>';
                                    
       if(isset($menu[$i]['icon']))
       {         
         $html .= '<table><tr><td style="padding-right: 5px;">' . image_tag('icons/' . $menu[$i]['icon']) . '</td><td>' . $menu[$i]['title']. '</td></tr></table>';
       }
       else
       {
         $html .= $menu[$i]['title'];
       }
       
         $html .= '</div></div>';     
              
          
        if(isset($menu[$i]['submenu']))
        {
          $html = renderMenu($menu[$i]['submenu'], $class, $id,$html,$level+1);
        }
        
        if(!isset($menu[$i+1]) and $level>0)
        {
          $html .= '
            <div class="' . $class . 'SubFooter level' . $level . '"></div>
          ';
        }  
        
        $html .= '
          </li>' . "\n";  
      }  
      
    $html .= '
      </ul>';
    
    return $html;    
  } 
  
  function renderYuiMenu($id, $m, $html = '', $level=0)
  {
    if($level==0)
    {
      $html .= '
        <div id="' . $id . '" class="yuimenubar yuimenubarnav" style="display: none">
          <div class="bd">
            <ul>' . "\n";
    }
    else
    {
      $html .= '
        <div id="' . $id  . mt_rand() . '" class="yuimenu">
          <div class="bd">
            <ul>' . "\n";
    }
    
    foreach($m as $v)
    {            
      if(isset($v['is_hr']))
      {
        if($v['is_hr']==true)
        {
          $html .= '</ul><ul>';
        }
      }
      
      $selected_class = '';
      if(isset($v['is_selected']))
      {
        if($v['is_selected']==true)
        {
          $selected_class = ' yuimenuselecteditem';
        }
      }
      
      if(isset($v['onClick']))
      {      
        $html .= '
              <li class="' . ($level==0?'yuimenubaritem':'yuimenuitem')  . $selected_class . '" onClick="' . $v['onClick'] . '"><span>' . $v['title'] . '</span>';    
      }
      elseif(isset($v['modalbox']))
      {      
        $html .= '
              <li class="' . ($level==0?'yuimenubaritem':'yuimenuitem') . $selected_class . '" ><a class="' . ($level==0?'yuimenubaritemlabel':'yuimenuitemlabel') . '" onClick="openModalBox(\'' . url_for($v['url'],true). '\')">' . $v['title'] . '</a>';    
      }
      elseif(isset($v['mamodalbox']))
      {      
        $html .= '
              <li class="' . ($level==0?'yuimenubaritem':'yuimenuitem') . $selected_class . '" onClick="openMultipleActionModalBox(\'' . url_for($v['url'],true). '\')"><span>' . $v['title'] . '</span>';    
      }
      elseif(isset($v['url']))
      {
        $html .= '
                <li class="' . ($level==0?'yuimenubaritem':'yuimenuitem') . $selected_class . '"><a ' . (isset($v['confirm'])?'onClick="return confirm(\'' . __('Are you sure?') . '\')"':''). ' class="' . ($level==0?'yuimenubaritemlabel':'yuimenuitemlabel') . '" href="' . (isset($v['url']) ? url_for($v['url']) : '#'). '"  ' . (isset($v['target'])? 'target="' . $v['target'] . '"' : ''). '>' . $v['title']. '</a>';
      }
      else
      {
        $html .= '
              <li class="' . ($level==0?'yuimenubaritem':'yuimenuitem') . $selected_class . ' "><span class="yuimenubaritemlevel' . $level . '">' . $v['title'] . '</span>';
      }

      if(isset($v['submenu']))
      {
        if(count($v['submenu'])>0)
        {
          $html = renderYuiMenu($id, $v['submenu'],$html,$level+1);
        }
      }

      $html .= '</li>' . "\n";
    }
    
    $html .= '
            </ul>
          </div>
        </div>';
        
    return $html;
  }
  
  function convertChoicesToYuiMenu($id, $choices,$multiple,$expanded)
  {
    if($multiple or $expanded)
    {
      return '';
    }
    
    $m = array();
    
    foreach($choices as $k=>$v)
    {                         
      $s = array();
      
      if(is_array($v))
      {
        foreach($v as $kk=>$vv)
        {
          $s[] = array('title'=>$vv,'onClick'=>'setFieldValueById(\'' .$id . '\',\'' . $kk . '\')');
        }
      }
      
      if(count($s)>0)
      {
        $m[] = array('title'=>$k,'submenu'=>$s);
      }
      else
      {
        $m[]=array('title'=>$v,'onClick'=>'setFieldValueById(\'' .$id . '\',\'' . $k . '\')');
      }
    }
    
    $m = array(array('title'=>'&nbsp;','submenu'=>$m));
    
    return  '<div id="yuiChoicesMenuContainer">'  . renderYuiMenu($id . '_yui_menu',$m)  . '</div>
      <script type="text/javascript">
        var oMenuBar' . $id . ';
        YAHOO.util.Event.onContentReady("' . $id . '_yui_menu", function () 
        {
            if(oMenuBar' . $id . ') oMenuBar' . $id . '.destroy();
            
            oMenuBar' . $id . ' = new YAHOO.widget.MenuBar("' . $id . '_yui_menu", {autosubmenudisplay: true,hidedelay: 350,submenuhidedelay: 0,showdelay: 150,lazyload: true });                        
            oMenuBar' . $id . '.render();                        
        });        
      </script>
    ';
  }  
