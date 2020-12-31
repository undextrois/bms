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
      <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
         <td>
         <table border="0" cellspacing="0" cellpadding="4">
         <tr>
            <td id="bms_normal"><a href="bms_module_add.php">Add New Module</a></td>
         </tr>
         </table>
         <table border="0" cellspacing="1" cellpadding="4" width="100%">
         <tr id="bms_htab">
            <td>Module Name</td>
            <td>Module Code</td>
            <td>Code Compare - PC</td>
            <td>Code Compare - Hard Lock</td>
            <td>Memory Allocation</td>
            <td>Memory Allocation Code</td>
            <td>Hard Lock ID Minimum Value</td>
            <td>Hard Lock ID Maximum Value</td>
            <td>Increment</td>
         </tr>
<?
 $m_sql = "SELECT m_ModuleName,m_ModuleCode,m_CCPC,m_CCHL,m_MA,m_MAC,m_HLIDMinVal,m_HLIDMaxVal,m_Increment ".
          "FROM BMS_Module_List ORDER BY m_ModuleName DESC";

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
            <td><?=$m_row->m_ModuleName?></td>
            <td><?=$m_row->m_ModuleCode?></td>
            <td><?=$m_row->m_CCPC?></td>
            <td><?=$m_row->m_CCHL?></td>
            <td><?=$m_row->m_MA?></td>
            <td><?=$m_row->m_MAC?></td>
            <td><?=$m_row->m_HLIDMinVal?></td>
            <td><?=$m_row->m_HLIDMaxVal?></td>
            <td><?=$m_row->m_Increment?></td>
         </tr>
<?
     }
     $m_db->BMS_mysql_free_result($m_result);
 }
?>
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
