<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 define ('BMS_CALLER_MODULE','ADM');

 BMS_session_check();

 require_once 'inc/templates/bms_top.php';
?>
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <table border="0" cellpadding="4" cellspacing="0" width="100%">
      <tr id="bms_top">
         <td align="left" valign="top">BMS Administration</td>
         <td align="right">
      </tr>
      </table>
      <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
         <td>
         <table border="0" cellspacing="0" cellpadding="4">
         <tr>
            <td id="bms_normal"><span id="bms_top">Generate Activation Code</span> - link to a page where admin user specify import info<br>
                about the end-user (co name, email add, etc, etc). After key-in the request code, user<br>
                is pointed to detailed page. And admin click 'send code via email'.</td>
         </tr>
         <tr>
            <td id="bms_normal"><span id="bms_top">Browser/Search function</span> - by cpu id, customer, email add, company, country, modules, etc.</td>
         </tr>
         <tr>
            <td id="bms_normal"><span id="bms_top">Download Database</span> - download entire table in .xls format</td>
         </tr>
         <tr>
            <td id="bms_normal"><span id="bms_top">Admin Users ACL</span> - generate code, browser/search, download listing</td>
         </tr>
         <tr>
            <td id="bms_normal"><span id="bms_top">Module Name Definition</span> -<br>
                module1 - BMS Basic Software<br>
                module2 - Reports<br>
                module3 - Service Tools<br>
                module4 - Module 4<br>
                module5 - Module 5<br>
                module6 - Module 6<br>
                module7 - Module 7<br>
                module8 - Module 8<br>
                module9 - Module 9<br>
                module10 - Module 10<br>
            </td>
         </tr>
         </table>
         </td>
         <td valign="bottom" align="right">
         </td>
      </tr>
      </table>
      </td>
   </tr>
   </table>
<?
 require_once 'inc/templates/bms_bottom.php'; 
 require_once 'inc/bms_cleanup.php';
?>
