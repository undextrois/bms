<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 define ('BMS_CALLER_MODULE','ADM');

 BMS_session_check();

 if (isset($_GET['view']) && isset($_GET['cpuid']))
 {
     $m_cpuid = trim($_GET['cpuid']);
     if ($m_cpuid != "")
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
   function doview(cpuid)
   {
       location = "bms_search.php?m=view&cpuid=" + cpuid;
   }
   </script>
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <form action="bms_search.php"  method="POST">
      <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
         <td id="bms_top" width="80">Search:</td><td><input type="text" name="string" maxlength="80"></td>
      </tr>
      <tr>
         <td id="bms_top" width="80">By:</td>
         <td>
            <select name="search_by">
               <option value="">Select Type</option>
               <option value="cpuid">CPUID</option>
               <option value="contact">Contact Person</option>
               <option value="company">Company</option>
               <option value="country">Country</option>
               <option value="requestcode">Request Code</option>
               <option value="module">Module</option>
            </select>
         </td>
      </tr>
      <tr>
         <td width="80"></td><td><input type="submit" value="GO"></td>
      </tr>
      <tr>
         <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
         <td colspan="2"><span id="bms_top">Search result:</span></td>
      </tr>
      </table>
      </form>
      <table border="0" cellspacing="1" cellpadding="4" width="100%">
      <tr id="bms_htab">
         <td>CPU ID</td>
         <td>Contact Person</td>
         <td>Company</td>
         <td>Country</td>
         <td>Request Code</td>
         <td>Option</td>
      </tr>
<?
 $m_string = isset($_POST['string']) ? $_POST['string'] : "";
 $m_string = trim($m_string);

 $m_search_by = isset($_POST['search_by']) ? $_POST['search_by'] : "";

 $m_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";

 $m_sql = "SELECT c.m_CPUID,c.m_ContactPerson,c.m_CompanyName,c.m_Country,c.m_RequestCode " .
          "FROM BMS_Clients AS c ";

 if ($m_method == 'POST' && in_array($m_search_by,array("cpuid","contact","company","country","requestcode","module")))
 {
     if ($m_search_by == "cpuid")
         $m_sql .= "WHERE c.m_CPUID = UPPER('" . mysql_escape_string($m_string) . "')";
     else if ($m_search_by == "contact")
         $m_sql .= "WHERE c.m_ContactPerson LIKE '" . strtoupper(mysql_escape_string($m_string)) . "'";
     else if ($m_search_by == "company")
         $m_sql .= "WHERE c.m_CompanyName = LIKE '" . strtoupper(mysql_escape_string($m_string)) . "'";
     else if ($m_search_by == "country")
         $m_sql .= "WHERE c.m_Country = LIKE '" . strtoupper(mysql_escape_string($m_string)) . "'";
     else if ($m_search_by == "requestcode")
         $m_sql .= "WHERE c.m_RequestCode = '" . mysql_escape_string($m_string) . "'";
 }

 $m_result = $m_db->BMS_mysql_query($m_sql);

 $m_left = 0;
 $m_col = 0;

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
         <td><?=$m_row->m_RequestCode?></td>
         <td><input type="button" value="view" onClick="doview('<?=$m_row->m_CPUID?>');">
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
         <td>&nbsp;</td>
         <td>&nbsp;</td>
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
