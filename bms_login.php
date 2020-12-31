<?
 require_once 'inc/bms_global.php';
 require_once 'inc/bms_login.php';

 $m_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";
 $m_error_message = "";
 $m_mod_request = "";

 if ($m_method == 'POST')
 {
     $m_user = "";
     $m_pass = "";
     $m_code = "";

     $m_login_type = "";

     $m_accept_input = true;

     $m_login_type = isset($_POST['type']) ? trim($_POST['type']) : "";

     if ($m_login_type != "" || in_array($m_login_type,array("RQ","LG")))
     {
        if ($m_login_type == "RQ")
        {
            $m_code = isset($_POST['code']) ? trim($_POST['code']) : "";
            $m_code_lng = strlen($m_code);

            if ($m_code_lng < 16 || $m_code_lng > 80)
                $m_accept_input = false;

            if ($m_accept_input && $m_code_lng == 16) {
                $m_accept_input = BMS_find_cpuid($m_code);
                $m_session_id = $m_code;
            }
            else if ($m_accept_input && $m_code_lng > 16) {
                $m_accept_input = BMS_find_request($m_code,$m_cpuid);
                $m_session_id = $m_cpuid;
            }

            if ($m_accept_input == false)
                $m_error_message = BMS_write_error("EC101: Invalid CPUID/request code");

            $m_mod_request = "CLI";
        }
        else
        {
           $m_user = isset($_POST['user']) ? trim($_POST['user']) : "";
           $m_pass = isset($_POST['pass']) ? trim($_POST['pass']) : "";

           $m_user_lng = strlen($m_user);
           $m_pass_lng = strlen($m_pass);

           if ($m_user_lng < 6 || $m_user_lng > 16)
               $m_accept_input = false;
           if ($m_pass_lng < 6 || $m_pass_lng > 16)
               $m_accept_input = false;
  
           if ($m_accept_input && !preg_match("/^[A-Za-z0-9]+$/sm",$m_user,$m_map))
               $m_accept_input = false;

           $m_accept_input = $m_accept_input ? BMS_find_user($m_user,$m_pass) : false;

           if ($m_accept_input == false)
               $m_error_message = BMS_write_error("EA102: Invalid username/password");

           $m_mod_request = "ADM";
           $m_session_id = $m_user;
        }

        if ($m_accept_input == true)
        {
            if (in_array($m_mod_request,array("CLI","ADM")))
                BMS_register_sessions($m_mod_request,$m_session_id);

            if ($m_mod_request == "CLI")
                BMS_redirect("bms_active_modules.php?mod=$m_mod_request");
            else if ($m_mod_request == "ADM")
                BMS_redirect("bms_admin_page.php");
        }
        else {
           $m_error_message = BMS_write_error("ED103: Invalid CPUID/request code");
        }
     }
     else {
        $m_error_message = BMS_write_error("EC104: Invalid CPUID/request code");
     }
 }

 require_once 'inc/templates/bms_login.php';

 require_once 'inc/bms_cleanup.php';
?>
