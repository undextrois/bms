<html>
   <head>
   <script language="javascript">
   function forward(id)
   {
      if (id == 0) location = "bms_admin_page.php";
      else if (id == 1) location = "bms_gen_ac.php";
      else if (id == 2) location = "bms_search.php";
      else if (id == 3) location = "bms_download.php";
      else if (id == 4) location = "bms_au_acl.php";
      else if (id == 5) location = "bms_logout.php";
      else if (id == 6) location = "bms_module_def.php";
   }
   </script>
   <link href="styles.css" rel="stylesheet" type="text/css" />
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
   <table border="0" cellspacing="0" cellpadding="8" width="800" bgcolor="#82a8b3">
   <tr>
   <td>
   <table border="0" cellspacing="0" cellpadding="0" width="100%">
   <tr>
   <td width="150" valign="top">
   <table border="1" cellspacing="0" cellpadding="6" width="100%">
   </tr>
      <td class="bms_menu" onClick="forward(0)">Main</td>
   </tr>
   </tr>
      <td class="bms_menu" onClick="forward(1)">Generate Activation Code</td>
   </tr>
   </tr>
      <td class="bms_menu" onClick="forward(2)">Browse/Search</td>
   </tr>
   </tr>
      <td class="bms_menu" onClick="forward(3)">Download Database</td>
   </tr>
   </tr>
      <td class="bms_menu" onClick="forward(4)">Admin Users ACL</td>
   </tr>
   </tr>
      <td class="bms_menu" onClick="forward(6)">Module Name Definition</td>
   </tr>
   </tr>
      <td>&nbsp;</td>
   </tr>
   </tr>
      <td class="bms_menu" onClick="forward(5)">Log Out</td>
   </tr>
   </table>
   </td>
   <td width="700" valign="top" bgcolor="#ffffff">
