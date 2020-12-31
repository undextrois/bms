<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 define ('BMS_CALLER_MODULE','ADM');

 BMS_session_check();

 $m_request = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";
 $m_tag = "";

 if ($m_request == 'POST')
 {
    $m_mod_request = isset($_POST['m']) ? $_POST['m'] : "";
    $m_cpuid = isset($_POST['cpuid']) ? $_POST['cpuid'] : "";
 }
 else
 {
    $m_mod_request = isset($_GET['m']) ? $_GET['m'] : "";
    $m_cpuid = isset($_GET['cpuid']) ? $_GET['cpuid'] : "";
    $m_tag = isset($_GET['t']) ? $_GET['t'] : "";
 }

 if (in_array($m_mod_request,array("add","view","set","unset","send","sent","confirm")) && $m_cpuid != "")
 {
     if ($m_mod_request == "confirm")
     {
         require_once 'inc/templates/bms_top.php';
         if (in_array($m_tag,array("set","unset")))
         {
             if ($m_tag == "set") print "Module has been activated.";
             else if ($m_tag == "unset") print "Module has been deactivated";
         }
         require_once 'inc/templates/bms_bottom.php';
         exit;
     }
     else if ($m_mod_request == "set")
     {
         $m_request_code = isset($_GET['rc']) ? $_GET['rc'] : "";
         $m_userid = session_is_registered("bmsu") ? $_SESSION['bmsu'] : "";
         if ($m_userid != "" && $m_request_code != "")
         {
             $m_sql = "UPDATE BMS_Active_Modules SET " .
                      "m_ActivatedBy = '" . mysql_escape_string($m_userid) . "', " .
                      "m_ActivationDate = NOW(), " .
                      "m_Active = 'Y' " .
                      "WHERE m_RequestCode = '" . mysql_escape_string($m_request_code) . "' " .
                      "   AND m_CPUID = '" . mysql_escape_string($m_cpuid) . "' " .
                      "   AND m_Active = 'N' ";

             $m_db->BMS_mysql_query($m_sql);

             BMS_redirect("bms_au_acl.php?m=confirm&cpuid=".$m_cpuid."&t=set");
         }
     }
     else if ($m_mod_request == "unset")
     {
         $m_request_code = isset($_GET['rc']) ? $_GET['rc'] : "";
         $m_userid = session_is_registered("bmsu") ? $_SESSION['bmsu'] : "";
         if ($m_userid != "" && $m_request_code != "")
         {
             $m_sql = "UPDATE BMS_Active_Modules SET " .
                      "m_ActivatedBy = '" . mysql_escape_string($m_userid) . "', " .
                      "m_ActivationDate = NOW(), " .
                      "m_Active = 'N' " .
                      "WHERE m_RequestCode = '" . mysql_escape_string($m_request_code) . "' " .
                      "   AND m_CPUID = '" . mysql_escape_string($m_cpuid) . "' " .
                      "   AND m_Active = 'Y' ";

             $m_db->BMS_mysql_query($m_sql);

             BMS_redirect("bms_au_acl.php?m=confirm&cpuid=".$m_cpuid."&t=unset");
         }
     }
     else if ($m_mod_request == "add")
     {
         require_once 'bms_module.php';

         if ($m_request == 'POST')
         {
             $m_module = isset($_POST['module']) ? $_POST['module'] : "";
             $m_type = isset($_POST['type']) ? $_POST['type'] : "";
             $m_module_name = "";
             $m_module_code = "";

             if ($m_type == 'PC')
             {
                 $m_request_code = $m_cpuid . $m_module;
                 $m_length = strlen($m_request_code) / 4;
                 $m_code = "";
                 $m_pos = 0;
                 $m_code_compare = 0;

                 $m_sql = "SELECT m_CCPC,m_ModuleName,m_ModuleCode FROM BMS_Module_List " .
                          "WHERE m_ModuleCode = '". mysql_escape_string($m_module) ."'";
                 $m_result = $m_db->BMS_mysql_query($m_sql);
                 if ($m_result)
                 {
                     $m_row = $m_db->BMS_mysql_fetch_object($m_result);
                     $m_code_compare = $m_row->m_CCPC;
                     $m_module_name = $m_row->m_ModuleName;
                     $m_module_code = $m_row->m_ModuleCode;
                     $m_db->BMS_mysql_free_result($m_result);
                 }

                 while ($m_length > 0)
                 {
                     if ($m_pos) $m_code.= "-";
                     $m_code.= substr($m_request_code,$m_pos,4);
                     $m_pos += 4;
                     $m_length--;
                 }
                 $m_activation_code = BMS_generate_code_PC($m_request_code,$m_code_compare);
                 $m_request_code = $m_code;
             }
             else if ($m_type == "HL")
             {
                 $m_hlid = isset($_POST['hlid']) ? trim($_POST['hlid']) : "";
                 $m_mac = "";
                 if (preg_match("/^\d+$/sm",$m_hlid,$m_map))
                 {
                     $m_request_code = $m_hlid . '-' . $m_module;
                     $m_code_compare = 0;

                     $m_sql = "SELECT m_CCHL,m_MAC,m_ModuleName,m_ModuleCode FROM BMS_Module_List " .
                              "WHERE m_ModuleCode = '". mysql_escape_string($m_module) ."'";
                     $m_result = $m_db->BMS_mysql_query($m_sql);
                     if ($m_result)
                     {
                         $m_row = $m_db->BMS_mysql_fetch_object($m_result);
                         $m_code_compare = $m_row->m_CCHL;
                         $m_module_name = $m_row->m_ModuleName;
                         $m_module_code = $m_row->m_ModuleCode;
                         $m_mac = $m_row->m_MAC;
                         $m_db->BMS_mysql_free_result($m_result);
                     }

                     $m_activation_code = BMS_generate_code_HL($m_request_code,$m_code_compare,$m_mac);
                 }
             }

             $m_sql = "INSERT INTO BMS_Active_Modules SET " .
                      "m_CPUID = UPPER('" . $m_cpuid . "')," .
                      "m_ModuleName = '" . mysql_escape_string($m_module_name) . "'," .
                      "m_ModuleCode = '" . mysql_escape_string($m_module_code) . "'," .
                      "m_RequestCode = '" . mysql_escape_string($m_request_code) . "'," .
                      "m_ActivationCode = '" . mysql_escape_string($m_activation_code) . "'," .
                      "m_Type = '" . mysql_escape_string($m_type) . "'";

             $m_db->BMS_mysql_query($m_sql);

             require_once 'inc/templates/bms_top.php';
?>
             Request Code: <?=$m_request_code?><br>
             Activation Code: <?=$m_activation_code?><br>
<?
             require_once 'inc/templates/bms_bottom.php';
             exit;
         }

         require_once 'inc/templates/bms_top.php';
?>
         <script language="javascript">
         function checkOption(val)
         {
            var field = document.forms[0].hlid;
            if (val == "HL") {
                field.disabled = false;
                field.value = "";
            }
            else {
                field.disabled = true;
                field.value = "";
            }
         }

         function numOnly()
         {
           var key = window.event.keyCode;
           if (key >= 48 && key <= 57) return true;
           else return false;
         }
         </script>
         <form action="bms_au_acl.php" method="POST">
         <table border="0" cellspacing="0" cellpadding="4" width="100%">
         <tr>
            <td id="bms_label">Module:</td>
            <td>
               <select name="module">
                  <option value="">Please Select A Module</option>
<?
                  $m_sql = "SELECT m_ModuleName,m_ModuleCode FROM BMS_Module_List ORDER BY m_ModuleName DESC";
                  $m_result = $m_db->BMS_mysql_query($m_sql);
                  if ($m_result)
                  {
                      while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
                      {                           
?>
                  <option value="<?=$m_row->m_ModuleCode?>"><?=$m_row->m_ModuleName?></option>
<?
                      }
                      $m_db->BMS_mysql_free_result($m_result); 
                  }
?>
               </select>
            </td>
         </tr>
         <tr>
            <td id="bms_label">License Type:</td>
            <td>
               <select name="type" onChange="checkOption(this.value)">
                  <option value="">Select License Type</option>
                  <option value="PC">PC License</option>
                  <option value="HL">Hard Lock License</option>
               </select>
            </td>
         </tr>
         <tr>
            <td id="bms_label">Hard Lock ID:</td><td><input type="text" name="hlid" maxlength="10" onKeyPress="return numOnly()"></td>
         </tr>
         <tr>
            <td id="bms_label"></td><td></td>
         </tr>
         <tr>
            <td id="bms_label"></td><td><input type="submit" value="Generate"></td>
         </tr>
         </table>
         <input type="hidden" name="m" value="add">
         <input type="hidden" name="cpuid" value="<?=$m_cpuid?>">
         </form>
<?
         require_once 'inc/templates/bms_bottom.php';
         require_once 'inc/bms_cleanup.php';
         exit;
     }
     else if ($m_mod_request == "send")
     {
         if (BMS_send_active_modules($m_cpuid) == true)
             BMS_redirect("bms_au_acl.php?m=sent&cpuid=".$m_cpuid);
         else {
             require_once 'inc/templates/bms_top.php';
             print "Send error";
             require_once 'inc/templates/bms_bottom.php';
             require_once 'inc/bms_cleanup.php';
             exit;
         }
     }
     else if ($m_mod_request == "sent")
     {                               
         require_once 'inc/templates/bms_top.php';
         print "Active modules sent";
         require_once 'inc/templates/bms_bottom.php';
         require_once 'inc/bms_cleanup.php';
         exit;
     }
     else if ($m_mod_request == "view")
     {
         require_once 'inc/templates/bms_top.php';

         $m_sql = "SELECT m_CPUID,m_ContactPerson,m_CompanyName,m_CreationDate,m_Country,m_Email,m_Phone,m_LastLogin " .
                  "FROM BMS_Clients " .
                  "WHERE m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "')";

         $m_result = $m_db->BMS_mysql_query($m_sql);
         if ($m_result)
         {
             $m_row = $m_db->BMS_mysql_fetch_object($m_result);
?>
         <table border="0" cellspacing="0" cellpadding="4" width="100%">
         <tr>
            <td id="bms_label">CPUID:</td><td id="bms_normal"><?=$m_row->m_CPUID?></td>
         </tr>
         <tr>
            <td id="bms_label">Contact Person:</td><td id="bms_normal"><?=$m_row->m_ContactPerson?></td>
         </tr>
         <tr>
            <td id="bms_label">Company Name:</td><td id="bms_normal"><?=$m_row->m_CompanyName?></td>
         </tr>
         <tr>
            <td id="bms_label">Country:</td><td id="bms_normal"><?=$m_row->m_Country?></td>
         </tr>
         <tr>
            <td id="bms_label">Date Created:</td><td id="bms_normal"><?=$m_row->m_CreationDate?></td>
         </tr>
         <tr>
            <td id="bms_label">E-Mail:</td><td id="bms_normal"><?=$m_row->m_Email?></td>
         </tr>
         <tr>
            <td id="bms_label">Phone:</td><td id="bms_normal"><?=$m_row->m_Phone?></td>
         </tr>
         <tr>
            <td id="bms_label">&nbsp;</td><td id="bms_normal" align="right"><img src="images/bms_send_to_email.jpg" border="0" style="cursor:hand" onClick="location='bms_au_acl.php?m=send&cpuid=<?=$m_cpuid?>'"></td>
         </tr>
         </table>
<?
            $m_db->BMS_mysql_free_result($m_result);
         }
?>
         <script language="javascript">
         function doactivate(cpuid,rc)
         {
            location = "bms_au_acl.php?m=set&cpuid=" + cpuid + "&rc=" + rc;
         }
         function dodeactivate(cpuid,rc)
         {
            location = "bms_au_acl.php?m=unset&cpuid=" + cpuid + "&rc=" + rc;
         }
         </script>
         <table border="0" cellspacing="1" cellpadding="4" width="100%">
         <tr id="bms_htab">
            <td>Module Name</td>
            <td>Module Code</td>
            <td>Type</td>
            <td>Active</td>
            <td>Activation Code</td>
            <td>Activation Date</td>
            <td>Request Code</td>
            <td>Activated By</td>
            <td>Option</td>
         </tr>
<?
 $m_left = 0;
 $m_col = 0;

 $m_sql = "SELECT m_CPUID,m_ModuleID,m_ModuleName,m_ModuleCode,m_Type,m_Active,m_ActivationCode,m_ActivationDate,m_RequestCode,m_ActivatedBy " .
          "FROM BMS_Active_Modules " .
          "WHERE m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "') " .
          "ORDER BY m_ActivationDate DESC";

 $m_result = $m_db->BMS_mysql_query($m_sql);
 if ($m_result)
 {
     while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
     {
        $m_col = 1 + ($m_left % 2);
        $m_left++;
?>
         <tr id="bms_col_<?=$m_col?>">
            <td><?=$m_row->m_ModuleName?></td>
            <td><?=$m_row->m_ModuleCode?></td>
            <td><?=$m_row->m_Type?></td>
            <td><?=$m_row->m_Active?></td>
            <td><?=$m_row->m_ActivationCode?></td>
            <td><?=$m_row->m_ActivationDate?></td>
            <td><?=$m_row->m_RequestCode?></td>
            <td><?=$m_row->m_ActivatedBy?></td>
            <td><? if ($m_row->m_Active != 'Y') {?><input type="button" value="Activate" class="bms_button" onClick="doactivate('<?=$m_row->m_CPUID?>','<?=$m_row->m_RequestCode?>')"><?} else {?><input type="button" value="Deactivate" class="bms_button" onClick="dodeactivate('<?=$m_row->m_CPUID?>','<?=$m_row->m_RequestCode?>')"><?}?></td>
         </tr>
<?
     }
 }

 if ($m_left < BMS_ROWS_PER_PAGE)
 {
     while ($m_left < BMS_ROWS_PER_PAGE)
     {
        $m_col = 1 + ($m_left % 2);
        $m_left++;
?>
         <tr id="bms_col_<?=$m_col?>">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
<?
     }
 }
?>
         </table>
<?
         require_once 'inc/templates/bms_bottom.php';
         require_once 'inc/bms_cleanup.php';
         exit;
     }
 }

 require_once 'inc/templates/bms_top.php';
?>
   <script language="javascript">
   function opt(id,param)
   {
       if (id == 1) location = "bms_au_acl.php?m=add&cpuid=" + param;
       else if (id == 2) location = "bms_au_acl.php?m=view&cpuid=" + param;
   }
   </script>
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <table border="0" cellspacing="1" cellpadding="4" width="100%">
      <tr id="bms_htab">
         <td>CPU ID</td>
         <td>Contact Person</td>
         <td>Company</td>
         <td>Country</td>
         <td>Option</td>
      </tr>
<?
 $m_sql = "SELECT m_CPUID,m_ContactPerson,m_CompanyName,m_Country FROM BMS_Clients " .
          "ORDER BY m_CreationDate DESC";

 $m_left = 0;
 $m_col = 0;

 $m_result = $m_db->BMS_mysql_query($m_sql);
 if ($m_result)
 {
     while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
     {
        $m_col = 1 + ($m_left % 2);
        $m_left++;
?>
      <tr id="bms_col_<?=$m_col?>">
         <td><?=$m_row->m_CPUID?></td>
         <td><?=$m_row->m_ContactPerson?></td>
         <td><?=$m_row->m_CompanyName?></td>
         <td><?=$m_row->m_Country?></td>
         <td align="center"><input type="button" value="add" class="bms_button" onClick="opt(1,'<?=$m_row->m_CPUID?>')"><input type="button" value="view" class="bms_button" onClick="opt(2,'<?=$m_row->m_CPUID?>')"></td>
      </tr>
<?
     }
     $m_db->BMS_mysql_free_result($m_result);
 }
 if ($m_left < BMS_ROWS_PER_PAGE)
 {
     while ($m_left < BMS_ROWS_PER_PAGE)
     {
        $m_col = 1 + ($m_left % 2);
        $m_left++;
?>
      <tr id="bms_col_<?=$m_col?>">
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td align="center">&nbsp;</td>
      </tr>
<?
     }
 }
?>
      </table>
      </td>
   </tr>
   </table>
<?
 require_once 'inc/templates/bms_bottom.php';
 require_once 'inc/bms_cleanup.php';
?>
