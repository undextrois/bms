<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 define ('BMS_CALLER_MODULE','CLI');

 BMS_session_check();

 $m_cpuid = $_SESSION['bmsu'];

 $m_send = isset($_GET['s']) ? true : false;
 $m_sent = isset($_GET['sent']) ? true : false;
 if ($m_send)
 {
     if (BMS_send_active_modules($m_cpuid) == true)
         BMS_redirect("bms_active_modules.php?sent=1");
     else {
         print "Send error";
         require_once 'inc/bms_cleanup.php';
         exit;
     }
 }
 else if ($m_sent) {
     print "Active modules sent";
     exit;
 }

 $m_sql= " SELECT m_CPUID,m_ContactPerson,m_CompanyName,m_CreationDate,m_Country,m_Email,m_Phone " .
         " FROM BMS_Clients " .
         " WHERE m_CPUID = UPPER('".mysql_escape_string($m_cpuid)."')";

 $m_contactPerson = "";
 $m_companyName = "";
 $m_creationDate = "";
 $m_country = "";
 $m_email = "";
 $m_phone = "";

 $m_result = $m_db->BMS_mysql_query($m_sql);
 if ($m_result)
 {
     $m_row = $m_db->BMS_mysql_fetch_object($m_result);

     $m_contactPerson = stripslashes($m_row->m_ContactPerson);
     $m_companyName = stripslashes($m_row->m_CompanyName);
     $m_creationDate = explode(" ",$m_row->m_CreationDate);
     $m_country = stripslashes($m_row->m_Country);
     $m_email = stripslashes($m_row->m_Email);
     $m_phone = $m_row->m_Phone;

     $m_db->BMS_mysql_free_result($m_result);
 }
?>
 <html>
   <head>
   <style type="text/css">
   #bms_top {
      font-size: 12px;
      font-family: verdana;
      font-weight: bold;
   }
   #bms_time {
      font-size: 12px;
      font-family: verdana;
      font-weight: normal;
   }

   #bms_normal {
      font-size: 10px;
      font-family: verdana;
      font-weight: normal;
   }

   #bms_htab {
      background-color: #4f81bd;
      color: #ffffff;
      font-family: verdana;
      font-size: 10px;
      font-weight: bold;
      text-align: center;
   }
   
   #bms_col_1 {
      background-color: #d0d8e8;
      font-family: verdana;
      font-size: 10px;
      font-weight: normal;
   }
   #bms_col_2 {
      background-color: #ffffff;
      font-family: verdana;
      font-size: 10px;
      font-weight: normal;
   }
   .bms_menu {
      border-width: 1px;
      border-style: solid;
      font-size: 9px;
      cursor:hand;
   }

   .bms_menu:hover {
      color: #800000;
   }

   </style>
   <script language="javascript">
   var ln = "bms_logout.php";

   function rel(id)
   {
   }

   function forward(id)
   {
      rel(id);
      if (id == 1) location = "bms_logout.php";
   }
   </script>

   </head>
<body>
   <table border="0" cellspacing="0" cellpadding="4" width="800">
   <tr>
   <td width="100" valign="top">
   <table border="0" cellspacing="0" cellpadding="4">
   </tr>
      <td class="bms_menu" onClick="forward(1)">Log Out</td>
   </tr>
   </table>
   </td>
   <td width="700">
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <table border="0" cellpadding="4" cellspacing="0" width="100%">
      <tr id="bms_top">
         <td align="left" valign="top">BMS Active Modules:</td>
         <td align="right">
         <table border="0" cellspacing="0" cellpadding="0">
         <tr id="bms_time">
            <td>&nbsp;</td>
            <td align="left">Date/Time:</td>
         </tr>
         <tr id="bms_time">
            <td>&nbsp;</td>
            <td align="right">created: <?=$m_creationDate[0]?></td>
         </tr>
         <tr id="bms_time">
            <td>&nbsp;</td>
            <td align="right"><?=$m_creationDate[1]?></td>
         </tr>
         </table>
      </tr>
      </table>
      <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
         <td>
         <table border="0" cellspacing="0" cellpadding="2">
         <tr id="bms_top">
            <td>User ID (same as CPU ID):</td>
            <td id="bms_normal"><?=$m_cpuid?>&nbsp;</td>
         </tr>
         <tr id="bms_top">
            <td>Contact Person:</td>
            <td id="bms_normal"><?=$m_contactPerson?>&nbsp;</td>
         </tr>
         <tr id="bms_top">
            <td>Email Add:</td>
            <td id="bms_normal"><?=$m_email?>&nbsp;</td>
         </tr>
         <tr id="bms_top">
            <td>Company:</td>
            <td id="bms_normal"><?=$m_company?>&nbsp;</td>
         </tr>
         <tr id="bms_top">
            <td>Country:</td>
            <td id="bms_normal"><?=$m_country?>&nbsp;</td>
         </tr>
         <tr id="bms_top">
            <td>Contact Info:</td>
            <td id="bms_normal"><?=$m_phone?>&nbsp;</td>
         </tr>
         </table>
         </td>
         <td valign="bottom" align="right">
         <img src="images/bms_send_to_email.jpg" border="0" style="cursor:hand" onClick="location='bms_active_modules.php?s=1'">
         </td>
      </tr>
      </table>
      </td>
   </tr>
   <tr>
      <td>
      <table border="0" cellspacing="1" cellpadding="2" width="100%">
      <tr id="bms_htab">
         <td>Module ID</td>
         <td>Module</td>
         <td>Active</td>
         <td>Activation Code</td>
         <td>Request Code</td>
         <td>Date/Time<br>
             Activated</td>
         <td>Activated by<br>
             (admin user)</td>
      </tr>
<?
 $m_sql = "SELECT m_ModuleID,m_ModuleName,m_Active,m_ActivationCode,m_RequestCode,m_ActivationDate,m_ActivatedBy ".
          " FROM BMS_Active_Modules ".
          " WHERE m_CPUID = UPPER('". mysql_escape_string($m_cpuid) . "')";
 $m_left = 0;
 $m_col = 0;

 $m_result = $m_db->BMS_mysql_query($m_sql);
 if ($m_result)
 {
     while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
     {
        $m_moduleID = $m_row->m_ModuleID;
        $m_moduleName = $m_row->m_ModuleName;
        $m_active = $m_row->m_Active;
        $m_activationCode = $m_row->m_ActivationCode;
        $m_requestCode = $m_row->m_RequestCode;
        $m_activationDate = $m_row->m_ActivationDate;
        $m_activatedBy = $m_row->m_ActivatedBy;

        $m_col = 1 + ($m_left % 2);
        $m_left++;
?>
      <tr id="bms_col_<?=$m_col?>">
         <td><?=$m_moduleID?></td>
         <td><?=$m_moduleName?></td>
         <td><?=$m_active?></td>
         <td><?=$m_activationCode?></td>
         <td><?=$m_requestCode?></td>
         <td><?=$m_activationDate?></td>
         <td><?=$m_activatedBy?></td>
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
         <td>&nbsp;<br>
             &nbsp;</td>
         <td>&nbsp;<br>
             &nbsp;</td>
      </tr>
<?
     }
 }
?>
      </table>
      </td>
   </tr>
   </table>
   </td>
   </tr>
   </table>
</body>
</html>
<?
 require_once 'inc/bms_cleanup.php';
?>
