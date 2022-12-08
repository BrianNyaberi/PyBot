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

class t
{
  public static function __($v)
  {
    return sfContext::getInstance()->getI18N()->__($v);
  }
  
  public static function renderJsDictionary()
  {
    $messages = array();
    $messages[] = "Loading...";
    $messages[] = "Are you sure?";
    $messages[] = "Please Select Items";    
    $messages[] = "Format";
    $messages[] = "Day";
    $messages[] = "Week";
    $messages[] = "Month";
    $messages[] = "Quarter";
    $messages[] = "Status";
    $messages[] = "Duration";
    $messages[] = "Progress";
    $messages[] = "Start Date";
    $messages[] = "Due Date";
    
    $messages[] = "Bold";
    $messages[] = "Italic";
    $messages[] = "Underline";
    $messages[] = "Left Align";
    $messages[] = "Numbered List";
    $messages[] = "Bulleted List";
    $messages[] = "Indent More";
    $messages[] = "Indent Less";
    $messages[] = "Strike";
    $messages[] = "Remove Formatting";
    $messages[] = "Horizontal Rule";
    $messages[] = "Image";
    $messages[] = "Text Color";
    $messages[] = "Background Color";
    $messages[] = "Source";
    $messages[] = "Code";    
    $messages[] = "Quote";
    $messages[] = "Paragraph";
        
    $content = '
    <script type="text/javascript">
      var I18NJSText = new Array()';
    foreach($messages as $value)
    {
      $content .= "
      I18NJSText['" . addslashes($value) . "']='" . addslashes(t::__($value)) . "';\n";
    }
    
    $content .= "</script>\n";
    
    return $content;
  }
}
  
