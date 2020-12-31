<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';
 require_once '3rdparty/Worksheet.php';
 require_once '3rdparty/Workbook.php';

 define ('BMS_CALLER_MODULE','ADM');

 BMS_session_check();

 $m_request = isset($_GET['get']) ? $_GET['get'] : null;
 $m_type = isset($_GET['type']) ? trim($_GET['type']) : "";
 $m_requestA = isset($_POST['list']) ? $_POST['list'] : null;
 $m_get_request = false;

 if ($m_requestA != null && is_array($m_requestA))
 {
     $m_get_request = true;
     $m_get_type = 'array';
 }
 else if ($m_request != null && in_array($m_request,array("am","ml","us")))
 {
     $m_get_request = true;
     $m_get_type = 'single';
 }

 if ($m_get_request == true)
 {
     header("Content-type: application/vnd.ms-excel");
     header("Content-Disposition: attachment; filename=book1.xls" );
     header("Expires: 0");
     header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
     header("Pragma: public");

     $workbook = new Workbook("-");

     if ($m_get_type == 'single') $m_db_loop = 1;
     else if ($m_get_type == 'array') $m_db_loop = count($m_requestA);

     while ($m_db_loop > 0)
     {
        if ($m_get_type == 'array')
        {
           $m_request = current($m_requestA);
           next($m_requestA);
        }

        if ($m_request == "am")
        {
            $m_worksheet = "Modules";
            $m_sql = "SELECT * FROM BMS_Active_Modules " .
                     "ORDER BY m_ActivationDate DESC";
        }
        else if ($m_request == "ml")
        {
            $m_worksheet = "Module List";
            $m_sql = "SELECT * FROM BMS_Module_List " .
                     "ORDER BY m_ModuleName DESC";
        }
        else if ($m_request == "us")
        {
            $m_worksheet = "Client List";
            $m_sql = "SELECT * FROM BMS_Clients " .
                     "ORDER BY m_ClientID DESC";
        }
         
        $worksheet =& $workbook->add_worksheet($m_worksheet);

        if ($m_request == "am")
        {
            $worksheet->write_string(1,1,"CPUID");
            $worksheet->write_string(1,2,"Module Name");
            $worksheet->write_string(1,3,"Module Code");
            $worksheet->write_string(1,4,"Active");
            $worksheet->write_string(1,5,"Activation Code");
            $worksheet->write_string(1,6,"Request Code");
            $worksheet->write_string(1,7,"Activation Date");
            $worksheet->write_string(1,8,"Activated By");
 
            $n = 3;

            $m_result = $m_db->BMS_mysql_query($m_sql);
            if ($m_result)
            {
                while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
                {
                   $worksheet->write_string($n,1,$m_row->m_CPUID);
                   $worksheet->write_string($n,2,$m_row->m_ModuleName);
                   $worksheet->write_string($n,3,$m_row->m_ModuleCode);
                   $worksheet->write_string($n,4,$m_row->m_Active);
                   $worksheet->write_string($n,5,$m_row->m_ActivationCode);
                   $worksheet->write_string($n,6,$m_row->m_RequestCode);
                   $worksheet->write_string($n,7,$m_row->m_ActivationDate);
                   $worksheet->write_string($n,8,$m_row->m_ActivatedBy);
                   $n++;
                }

                $m_db->BMS_mysql_free_result($m_result);
            }
        }
        else if ($m_request == "ml")
        {
            $worksheet->write_string(1,1,"Module Name");
            $worksheet->write_string(1,2,"Module Code");
            $worksheet->write_string(1,3,"Code Compare - PC");
            $worksheet->write_string(1,4,"Code Compare - Hard Lock");
            $worksheet->write_string(1,5,"Memory Allocation");
            $worksheet->write_string(1,6,"Memory Allocation Code");
            $worksheet->write_string(1,7,"Hard Lock ID Minimum Value");
            $worksheet->write_string(1,8,"Hard Lock ID Maximum Value");
            $worksheet->write_string(1,9,"Increment");

            $n = 3;
 
            $m_result = $m_db->BMS_mysql_query($m_sql);
            if ($m_result)
            {
                while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
                {
                   $worksheet->write_string($n,1,$m_row->m_ModuleName);
                   $worksheet->write_string($n,2,$m_row->m_ModuleCode);
                   $worksheet->write_string($n,3,$m_row->m_CCPC);
                   $worksheet->write_string($n,4,$m_row->m_CCHL);
                   $worksheet->write_string($n,5,$m_row->m_MA);
                   $worksheet->write_string($n,6,$m_row->m_MAC);
                   $worksheet->write_string($n,7,$m_row->m_HLIDMinVal);
                   $worksheet->write_string($n,8,$m_row->m_HLIDMaxVal);
                   $worksheet->write_string($n,9,$m_row->m_Increment);
                   $n++;
                }
 
                $m_db->BMS_mysql_free_result($m_result);
            }
        }
        else if ($m_request == "us")
        {
            $worksheet->write_string(1,1,"CPUID");
            $worksheet->write_string(1,2,"Contact Person");
            $worksheet->write_string(1,3,"Company Name");
            $worksheet->write_string(1,4,"Creation Date");
            $worksheet->write_string(1,5,"Country");
            $worksheet->write_string(1,6,"Email");
            $worksheet->write_string(1,7,"Phone");
  
            $n = 3;
 
            $m_result = $m_db->BMS_mysql_query($m_sql);
            if ($m_result)
            {
                while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
                {
                   $worksheet->write_string($n,1,$m_row->m_CPUID);
                   $worksheet->write_string($n,2,$m_row->m_ContactPerson);
                   $worksheet->write_string($n,3,$m_row->m_CompanyName);
                   $worksheet->write_string($n,4,$m_row->m_CreationDate);
                   $worksheet->write_string($n,5,$m_row->m_Country);
                   $worksheet->write_string($n,6,$m_row->m_Email);
                   $worksheet->write_string($n,7,$m_row->m_Phone);
                   $n++;
                }

                $m_db->BMS_mysql_free_result($m_result);
            }
        }

        $m_db_loop--;
     }
     $workbook->close();

     exit;
 }

 require_once 'inc/templates/bms_top.php';
?>
   <script language="javascript">
   function dl(id)
   {
      if (id == 1) location = "bms_download.php?get=am";
      if (id == 2) location = "bms_download.php?get=ml";
      if (id == 3) location = "bms_download.php?get=us";
   }
   </script>   
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <table border="0" cellpadding="4" cellspacing="0" width="100%">
      <tr id="bms_top">
         <td align="left" valign="top">BMS Download:</td>
         <td align="right">
      </tr>
      </table>
      <form action="bms_download.php" method="POST">
      <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
         <td width="20"><input type="checkbox" name="list" value="am"></td>
         <td id="bms_label">Active Modules</td>
         <td width="20"><input name="get" type="button" class="bms_button" onClick="dl(1)" value="Download"></td>
      </tr>
      <tr>
         <td width="20"><input type="checkbox" name="list" value="us"></td>
         <td id="bms_label">Users</td>
         <td width="20"><input name="get" type="button" class="bms_button" onClick="dl(2)" value="Download"></td>
      </tr>
      <tr>
         <td width="20"><input type="checkbox" name="list" value="ml"></td>
         <td id="bms_label">Module List</td>
         <td width="20"><input name="get" type="button" class="bms_button" onClick="dl(3)" value="Download"></td>
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
