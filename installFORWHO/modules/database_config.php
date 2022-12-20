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
<div class="infoBlock"><h1>Database config</h1></div>

<?php if(isset($_GET['db_error'])) echo '<div class="error_text">' . $_GET['db_error'] . '</div>';?>

<form name="db_config" action="index.php?step=qdpm_config" method="post">
<table class="formTable">
  <tr>
    <td>Database host:</td>
    <td><input type="text" name="db_host" id="db_host" value="localhost"></td>
    <td>The address of the database server in the form of a hostname or IP address.</td>
  </tr>
  <tr>
    <td>Database port:</td>
    <td><input type="text" name="db_port" id="db_port" value=""></td>
    <td>MySQL Connection Port. <i>(Leave this blank unless you know the server operates on a non-standard port.)</i></td>
  </tr>
  <tr>
    <td>Database name:</td>
    <td><input type="text" name="db_name" id="db_name" value="qdpm"></td>
    <td>The name of the database to hold the data in.</td>
  </tr>
  <tr>
    <td>DB username:</td>
    <td><input type="text" name="db_username" id="db_username" value=""></td>
    <td>The username used to connect to the database server.</td>
  </tr>
  <tr>
    <td>DB password:</td>
    <td><input type="text" name="db_password" id="db_password" value=""></td>
    <td>The password that is used together with the username to connect to the database server.</td>
  </tr>
</table>

<div><input type="submit" value="Install Database"  class="btn"></div>

</form>
