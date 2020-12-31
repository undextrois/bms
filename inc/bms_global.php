<?
 require_once 'bms_config.php'; 

 class BMS_DB
 {
    var $m_user;
    var $m_host;
    var $m_pass;
    var $m_db;
    var $m_dh;
    var $m_dl;

    function BMS_DB($host,$db,$user,$pass)
    {
       $this->m_host = $host;
       $this->m_db = $db;
       $this->m_user = $user;
       $this->m_pass = $pass;
    }

    function BMS_mysql_connect()
    {
       $this->m_dh = mysql_connect($this->m_host,$this->m_user,$this->m_pass);
       if (!$this->m_db)
          die ("error: unable to connect to server");
       $this->m_dl = mysql_select_db($this->m_db,$this->m_dh);
       if (!$this->m_dl)
          die ("error: unable to connect to database");
    }

    function BMS_mysql_query($m_sql)
    {
       return mysql_query($m_sql);
    }

    function BMS_mysql_fetch_object($m_result)
    {
       return mysql_fetch_object($m_result); 
    }

    function BMS_mysql_num_rows($m_result)
    {
       return mysql_num_rows($m_result);
    }

    function BMS_mysql_free_result($m_result)
    {
       return mysql_free_result($m_result);
    }

    function BMS_mysql_close()
    {
       @mysql_close($this->m_dh);
    }
 };

 function BMS_send_active_modules($m_cpuid)
 {
    global $m_db;

    $m_email = "";
    $m_sql = "SELECT m_Email FROM BMS_Clients WHERE m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "')";
    $m_result = $m_db->BMS_mysql_query($m_sql);
    if (!$m_result) return false;
    $m_row = $m_db->BMS_mysql_fetch_object($m_result);
    if (trim($m_row->m_Email) == "") {
        $m_db->BMS_mysql_free_result($m_result);
        return false;
    }
    $m_email = $m_row->m_Email;
    $m_db->BMS_mysql_free_result($m_result);    

    $m_sql = "SELECT m_ModuleName,".
             "       m_ModuleCode,".
             "       m_Active,".
             "       m_RequestCode,".
             "       m_ActivationCode,".
             "       m_ActivationDate,".
             "       m_ActivatedBy, ".
             "       m_Type " .
             "FROM BMS_Active_Modules " .
             "WHERE m_Active = 'Y' ".
             "   AND m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) ."')";

    $m_result = $m_db->BMS_mysql_query($m_sql);
    if ($m_result)
    {
        $m_mail = "";
        while ($m_row = $m_db->BMS_mysql_fetch_object($m_result))
        {
           if ($m_mail != "") $m_mail = "\n\n";

           $m_mail.= "Module Name     : $m_row->m_ModuleName\n";
           if ($m_row->m_Type == "HL") {
               $m_mail.= "Type: Hard Lock\n";
           }
           else {
               $m_mail.= "Type: PC\n";
               $m_mail.= "Module Code     : $m_row->m_ModuleCode\n";
           }
           $m_mail.= "Request Code    : $m_row->m_RequestCode\n";
           $m_mail.= "Activation Code : $m_row->m_ActivationCode\n";
           $m_mail.= "Activation Date : $m_row->m_ActivationDate\n";
        }

        $m_db->BMS_mysql_free_result($m_result);

        @mail($m_email,'Activated Modules',$m_mail);
    }
    else {
       return false;
    }

    return true;
 }

 function BMS_write_value($m_value)
 {
    if ($m_value != "") return " value=\"$m_value\"";
    else return "";
 }

 $m_db = new BMS_DB($BMS_DB_host,$BMS_DB_db,$BMS_DB_user,$BMS_DB_pass);
 $m_db->BMS_mysql_connect();
?>
