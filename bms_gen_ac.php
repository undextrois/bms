<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 define ('BMS_CALLER_MODULE','ADM');

 BMS_session_check();

 $m_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";

 $m_cpuid = "";
 $m_contact = "";
 $m_company = "";
 $m_country = "";
 $m_email = "";
 $m_phone = "";

 $m_confirm = isset($_GET['confirm']) ? $_GET['confirm'] : "";
 $m_cpuid = isset($_GET['cpuid']) ? $_GET['cpuid'] : "";

 if ($m_confirm)
 {
     require_once 'inc/templates/bms_top.php';
?>
          <span id="bms_top">The information bellow has been added to the database</span>
<?
     $m_sql = "SELECT m_CPUID,m_ContactPerson,m_CompanyName,m_Country,m_Email,m_Phone " .
              "FROM BMS_Clients WHERE m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "')";
     $m_result = $m_db->BMS_mysql_query($m_sql);
     if ($m_result)
     {
         $m_row = $m_db->BMS_mysql_fetch_object($m_result);
?>
          <table border="0" cellspacing="0" cellpadding="4">
          <tr>
             <td id="bms_label">CPUID :</td><td><?=$m_row->m_CPUID?></td>
          </tr>
          <tr>
             <td id="bms_label">Contact Person:</td><td><?=$m_row->m_ContactPerson?></td>
          </tr>
          <tr>
             <td id="bms_label">Company Name:</td><td><?=$m_row->m_CompanyName?></td>
          </tr>
          <tr>
             <td id="bms_label">Country:</td><td><?=$m_row->m_Country?></td>
          </tr>
          <tr>
             <td id="bms_label">E-Mail:</td><td><?=$m_row->m_Email?></td>
          </tr>
          <tr>
             <td id="bms_label">Phone:</td><td><?=$m_row->m_Phone?></td>
          </tr>
          </table>
<?
     }
     require_once 'inc/templates/bms_bottom.php';
     exit;
 }

 $m_error_message = "";

 $m_marker = array(
    "cpuid" => "bms_label",
    "contact" => "bms_label",
    "company" => "bms_label",
    "country" => "bms_label",
    "email" => "bms_label",
    "phone" => "bms_label"
 );

 if ($m_method == 'POST')
 {
     $m_state = true;

     $m_cpuid = isset($_POST['cpuid']) ? trim($_POST['cpuid']) : "";
     $m_contact = isset($_POST['contact']) ? trim($_POST['contact']) : "";
     $m_company = isset($_POST['company']) ? trim($_POST['company']) : "";
     $m_country = isset($_POST['country']) ? trim($_POST['country']) : "";
     $m_email = isset($_POST['email']) ? trim($_POST['email']) : "";
     $m_phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";

     if ($m_cpuid == "" || strlen($m_cpuid) > 16) {
         $m_state = false;
         $m_marker['cpuid'] = "bms_error";
     }
     if ($m_contact == "" || strlen($m_contact) > 64) {
         $m_state = false;
         $m_marker['contact'] = "bms_error";
     }
     if ($m_company == "" || strlen($m_company) > 64) {
         $m_state = false;
         $m_marker['company'] = "bms_error";
     }
     if ($m_country == "" || strlen($m_country) > 16) {
         $m_state = false;
         $m_marker['country'] = "bms_error";
     }
     if ($m_email == "" || strlen($m_email) > 32) {
         $m_state = false;
         $m_marker['email'] = "bms_error";
     }
     else {
         if (!preg_match("/^([A-Za-z0-9]+(\_|\.|[A-Za-z0-9]+)*@([A-Za-z0-9]+\.[A-Za-z0-9])+\.([A-Za-z]+)$/sm",$m_email,$m_map))
         {
             $m_state = false;
             $m_marker['email'] = "bms_error";
         }
     }
     if ($m_phone == "" || strlen($m_phone) > 32) {
         $m_state = false;
         $m_marker['phone'] = "bms_error";
     }
     else {
         if (!preg_match("/^[+]?\d+$/sm",$m_phone,$m_map))
         {
             $m_state = false;
             $m_marker['phone'] = "bms_error";
         }
     }

     if ($m_state != false)
     {
         $m_sql = "SELECT COUNT(m_CPUID) AS m_Count FROM BMS_Clients WHERE m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "')";
         $m_result = $m_db->BMS_mysql_query($m_sql);
         if ($m_result)
         {
             $m_row = $m_db->BMS_mysql_fetch_object($m_result);
             if ($m_row->m_Count > 0) {
                 $m_state = false;
                 $m_error_message = BMS_write_error("CPUID Exists");
             }
             $m_db->BMS_mysql_free_result($m_result);
             
         }
         if ($m_state == true)
         {
             $m_sql = "INSERT INTO BMS_Clients SET " .
                      "m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "')," .
                      "m_ContactPerson = UPPER('" . mysql_escape_string($m_contact) . "')," .
                      "m_CompanyName = UPPER('" . mysql_escape_string($m_company) . "')," .
                      "m_Country = UPPER('" . mysql_escape_string($m_county) . "')," .
                      "m_Email = '" . mysql_escape_string($m_email) . "'," .
                      "m_Phone = '" . mysql_escape_string($m_phone) . "'";

             $m_db->BMS_mysql_query($m_sql);

             BMS_redirect("bms_gen_ac.php?confirm=1&cpuid=" . $m_cpuid);
         }
     }
     else {
        $m_error_message = BMS_write_error("* Please correct the errors below<br><br>");
     }
 }

 require_once 'inc/templates/bms_top.php';
?>
   <table border="0" cellspacing="0" cellpadding="4" width="100%">
   <tr>
      <td>
      <?=$m_error_message?>
      <form action="bms_gen_ac.php" method="POST">
      <table border="0" cellspacing="0" cellpadding="4" width="100%">
      <tr>
         <td id="<?=$m_marker['cpuid']?>" width="100">CPUID:</td><td><input type="text" name="cpuid" maxlength="16"<?=BMS_write_value($m_cpuid)?>></td>
      </tr>
      <tr>
         <td id="<?=$m_marker['contact']?>" width="100">Contact Person:</td><td><input type="text" name="contact" maxlength="64"<?=BMS_write_value($m_contact)?>></td>
      </tr>
      <tr>
         <td id="<?=$m_marker['company']?>" width="100">Company Name:</td><td><input type="text" name="company" maxlength="64"<?=BMS_write_value($m_company)?>></td>
      </tr>
      <tr>
         <td id="<?=$m_marker['country']?>" width="100">Country:</td><td><input type="text" name="country" maxlength="16"<?=BMS_write_value($m_country)?>></td>
      </tr>
      <tr>
         <td id="<?=$m_marker['email']?>" width="100">E-Mail:</td><td><input type="text" name="email" maxlength="32"<?=BMS_write_value($m_email)?>></td>
      </tr>
      <tr>
         <td id="<?=$m_marker['phone']?>" width="100">Phone:</td><td><input type="text" name="phone" maxlength="32"<?=BMS_write_value($m_phone)?>></td>
      </tr>
      <tr>
         <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td><td><input type="submit" value="Add"></td>
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
