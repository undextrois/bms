<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 define ('BMS_CALLER_MODULE','ADM');

 BMS_session_check();

 $m_request = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";
 $m_error_message = "";
 $m_mod_request = isset($_GET['confirm']) ? intval($_GET['confirm']) : 0;

 if ($m_mod_request == 1)
 {
     require_once 'inc/templates/bms_top.php';
     print "Module has been added successfully";
     require_once 'inc/templates/bms_bottom.php';
     exit;
 }

 if ($m_request == 'POST')
 {
     $m_modname = isset($_POST['modname']) ? trim($_POST['modname']) : "";
     $m_modcode = isset($_POST['modcode']) ? trim($_POST['modcode']) : "";
     $m_ccpc = isset($_POST['ccpc']) ? intval($_POST['ccpc']) : 0;
     $m_cchl = isset($_POST['cchl']) ? intval($_POST['cchl']) : 0;
     $m_ma = isset($_POST['ma']) ? trim($_POST['ma']) : "";
     $m_mac = isset($_POST['mac']) ? trim($_POST['mac']) : "";
     $m_hlmin = isset($_POST['hlmin']) ? intval($_POST['hlmin']) : 0;
     $m_hlmax = isset($_POST['hlmax']) ? intval($_POST['hlmax']) : 0;
     $m_inc = isset($_POST['inc']) ? intval($_POST['inc']) : 0;

     $m_state = true;
     if ($m_modname == "" || $m_modcode == "") {
         $m_error_message = BMS_write_error("error adding new module");
         $m_state = false; 
     }
     if ($m_ma == "" || $m_mac == "") {
         $m_error_message = BMS_write_error("error adding new module");
         $m_state = false;
     }
     if (!preg_match("/^(\d+\-)+\d+$/sm",$m_ma,$m_map)) {
         $m_error_message = BMS_write_error("invalid memory address format");
         $m_state = false;
     }
     if (!preg_match("/^(\w+\-)+\w+$/sm",$m_mac,$m_map)) {
         $m_error_message = BMS_write_error("invalid memory address code format");
         $m_state = false;
     }

     if ($m_state == true)
     {
         $m_sql = "INSERT INTO BMS_Module_List SET " .
                  "m_ModuleName = '" . mysql_escape_string($m_modname) . "'," .
                  "m_ModuleCode = '" . mysql_escape_string($m_modcode) . "'," .
                  "m_CCPC = '" . $m_ccpc . "'," .
                  "m_CCHL = '" . $m_cchl . "'," .
                  "m_MA = '" . $m_ma . "'," .
                  "m_MAC = '" . $m_mac . "'," .
                  "m_HLIDMinVal = '" . $m_hlmin . "'," .
                  "m_HLIDMaxVal = '" . $m_hlmax . "'," .
                  "m_Increment = '" . $m_inc . "'";
         $m_db->BMS_mysql_query($m_sql);

         BMS_redirect("bms_module_add.php?confirm=1");
     }
 }

 require_once 'inc/templates/bms_top.php';
?>
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <?=$m_error_message?>  
      <form action="bms_module_add.php" method="POST">
      <table border="0" cellspacing="0" cellpadding="4">
      <tr>
         <td id="bms_label">Module Name:</td><td><input type="text" name="modname" maxlength="32"></td>
      </tr>
      <tr>
         <td id="bms_label">Module Code:</td><td><input type="text" name="modcode" maxlength="12"></td>
      </tr>
      <tr>
         <td id="bms_label">Code Compare - PC:</td><td><input type="text" name="ccpc" maxlength="10"></td>
      </tr>
      <tr>
         <td id="bms_label">Code Compare - Hard Lock:</td><td><input type="text" name="cchl" maxlength="10"></td>
      </tr>
      <tr>
         <td id="bms_label">Memory Allocation:</td><td><input type="text" name="ma" maxlength="16"></td>
      </tr>
      <tr>
         <td id="bms_label">Memory Allocation Code:</td><td><input type="text" name="mac" maxlength="10"></td>
      </tr>
      <tr>
         <td id="bms_label">Hard Lock ID Minimum Value:</td><td><input type="text" name="hlmin" maxlength="10"></td>
      </tr>
      <tr>
         <td id="bms_label">Hard Lock ID Maximum Value:</td><td><input type="text" name="hlmax" maxlength="10"></td>
      </tr>
      <tr>
         <td id="bms_label">Increment:</td><td><input type="text" name="inc" maxlength="10"></td>
      </tr>
      <tr>
         <td colspan="2"></td>
      </tr>
      <tr>
         <td>&nbsp;</td><td><input type="submit" value="Add Module"></td>
      </tr>
      </table>
      </form>
      </td>
   </tr>
   </table>
<?
 require_once 'inc/templates/bms_bottom.php'; 
 require_once 'inc/bms_cleanup.php';
?>
