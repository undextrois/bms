<?
 function BMS_find_cpuid($m_cpuid)
 {
    global $m_db;

    $m_state = false;

    $m_sql = "SELECT m_ClientID,m_CPUID FROM BMS_Clients " .
             "WHERE m_CPUID = UPPER('" . mysql_escape_string($m_cpuid) . "')";

    $m_result = $m_db->BMS_mysql_query($m_sql);
    if ($m_result)
    {
        $m_db->BMS_mysql_free_result($m_result);
        $m_state = true;
    }

    return $m_state;
 }

 function BMS_find_request($m_code,&$m_cpuid)
 {
    global $m_db;

    $m_state = false;
    $m_cpuid = "";

    $m_sql = "SELECT m_CPUID FROM BMS_Active_Modules " .
             "WHERE m_RequestCode = '" . mysql_escape_string($m_code) . "'";

    $m_result = $m_db->BMS_mysql_query($m_sql);
    if ($m_result)
    {
        $m_row = $m_db->BMS_mysql_fetch_object($m_result);
        $m_cpuid = $m_row->m_CPUID;
        $m_db->BMS_mysql_free_result($m_result);
    }

    return $m_state;
 }

 function BMS_find_user($m_user,$m_pass)
 {
    global $m_db;

    $m_state = false;

    $m_sql = "SELECT m_UserID,m_UserNo,m_Passwd FROM BMS_Users " .
             "WHERE m_UserID = UPPER('" . mysql_escape_string($m_user) . "') " .
             "   AND m_Passwd = '" . mysql_escape_string($m_pass) . "'";

    $m_result = $m_db->BMS_mysql_query($m_sql);
    if ($m_result)
    {
        $m_db->BMS_mysql_free_result($m_result);
        $m_state = true;
    }

    return $m_state;
 }

 function BMS_redirect($m_url)
 {
    global $m_db;

    $m_db->BMS_mysql_close();

    header("location: " . $m_url);
    exit;
 }

 function BMS_register_sessions($m_mod_request,$m_userid)
 {
    global $m_db;

    $m_keymap = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $m_keyid = 0;    
    $m_keylen = strlen($m_keymap) - 1;
    $m_keyval = rand(0,$m_keylen);
    $m_hashval = "";
    $m_hashid = 0;

    BMS_session_start();
    BMS_unregister_previous();

    session_register("bmsu");
    session_register("bmsk");
    session_register("bmsh");

    $m_session_id = session_id();
    $m_key1 = "";

    srand((double)microtime() * 1000000);

    while ($m_keyid < 16)
    {
       $m_keyval+= rand(0,$m_keylen) + ord($m_mod_request{$m_keyval % 3});
       $m_key1.= $m_keymap{$m_keyval % $m_keylen};
       $m_keyid++; 
    }

    $m_hashid = ord($m_userid{0}) + ord($m_key1{0}) + ord($m_key1{15});
    $m_hashid = $m_hashid % 5;

    if ($m_hashid == 0)
        $m_hashval = md5($m_hashid . $m_userid . $m_key1);
    else if ($m_hashid == 1)
        $m_hashval = md5($m_userid . $m_hashid . $m_key1);
    else if ($m_hashid == 2)
        $m_hashval = md5($m_key1 . $m_userid . $m_hashid);
    else if ($m_hashid == 3)
        $m_hashval = md5($m_key1 . $m_hashid . $m_userid);
    else if ($m_hashid == 4)
        $m_hashval = md5($m_userid . $m_key1 . $m_hashid);
    else if ($m_hashid == 5)
        $m_hashval = md5($m_hashid . $m_key1 . $m_userid);

    $_SESSION['bmsu'] = $m_userid;
    $_SESSION['bmsk'] = $m_key1;
    $_SESSION['bmsh'] = $m_hashval;

    $m_sql = "INSERT INTO BMS_Sessions (m_SessionID,m_UserID,m_Key1,m_Type,m_DateCreated,m_TimeCheck,m_Flag) " .
             "VALUES('" . mysql_escape_string($m_session_id) . "','" . mysql_escape_string($m_userid) . "','" . mysql_escape_string($m_key1) . "','" . mysql_escape_string($m_mod_request) . "',NOW(),NOW(),1)";

    $m_db->BMS_mysql_query($m_sql);
 }

 function BMS_session_start()
 {
    session_start();
 }

 function BMS_unregister_previous()
 {
    BMS_session_unregister();
 }

 function BMS_session_check()
 {
    global $m_db;

    $m_state = false;

    BMS_session_start();

    if (!session_is_registered("bmsu"))
        BMS_session_destroy();
    if (!session_is_registered("bmsk"))
        BMS_session_destroy();
    if (!session_is_registered("bmsh"))
        BMS_session_destroy();

    $m_session_id = session_id();
    $m_key1 = $_SESSION['bmsk'];
    $m_userid = $_SESSION['bmsu'];

    $m_hashval = $_SESSION['bmsh'];
    $m_hashlen = strlen($m_hashval);
    $m_hashcval = "";
    $m_hashid = 0;

    if ($m_hashlen == 32)
    {
        $m_hashid = ord($m_userid{0}) + ord($m_key1{0}) + ord($m_key1{15});
        $m_hashid = $m_hashid % 5;

        if ($m_hashid == 0)
            $m_hashcval != md5($m_hashid . $m_userid . $m_key1);
        else if ($m_hashid == 1)
            $m_hashcval = md5($m_userid . $m_hashid . $m_key1);
        else if ($m_hashid == 2)
            $m_hashcval = md5($m_key1 . $m_userid . $m_hashid);
        else if ($m_hashid == 3)
            $m_hashcval = md5($m_key1 . $m_hashid . $m_userid);
        else if ($m_hashid == 4)
            $m_hashcval = md5($m_userid . $m_key1 . $m_hashid);
        else if ($m_hashid == 5)
            $m_hashcval = md5($m_hashid . $m_key1 . $m_userid);

        if ($m_hashcval == $m_hashval)
        {
            $m_sql = "SELECT m_SessionID,m_UserID,m_Key1,m_Type,m_Flag FROM BMS_Sessions " .
                     "WHERE m_SessionID = '" . mysql_escape_string($m_session_id) . "' " .
                     "   AND m_Key1 = '" . mysql_escape_string($m_key1) . "' " .
                     "   AND m_UserID = '" . mysql_escape_string($m_userid) . "' " .
                     "   AND m_Flag = 1";

            $m_result = $m_db->BMS_mysql_query($m_sql);
            if ($m_result)
            {
                $m_row = $m_db->BMS_mysql_fetch_object($m_result);
                if (defined('BMS_CALLER_MODULE') && $m_row->m_Type == BMS_CALLER_MODULE)
                {
                    $m_state = true;
                    $m_db->BMS_mysql_free_result($m_result);
                    BMS_update_time($m_session_id,$m_userid);
                }
                else {
                   $m_db->BMS_mysql_free_result($m_result);
                }
            }
        }
    }

    if ($m_state == false)
        BMS_session_destroy();
 }

 function BMS_update_time($m_session_id,$m_userid)
 {
    global $m_db;

    $m_sql = "UPDATE BMS_Sessions SET " .
             "m_TimeCheck = NOW() " .
             "WHERE m_SessionID = '" . mysql_escape_string($m_session_id). "' " .
             "AND m_UserID = '" . mysql_escape_string($m_userid) . "'";

    $m_db->BMS_mysql_query($m_sql);
 }

 function BMS_session_unregister()
 {
    global $m_db;

    if (session_is_registered("bmsu"))
    {
        $m_userid = $_SESSION['bmsu'];

        $m_sql = "UPDATE BMS_Sessions SET m_Flag = '2'," .
                 "m_EndTime = NOW() " .
                 "WHERE m_UserID = '" . mysql_escape_string($m_userid) . "'";

        $m_db->BMS_mysql_query($m_sql);

        session_unregister("bmsu");
    }

    if (session_is_registered("bmsk"))
        session_unregister("bmsk");

    if (session_is_registered("bmsh"))
        session_unregister("bmsh");
 }

 function BMS_session_destroy()
 {
    BMS_session_unregister();
    @session_destroy();

    BMS_redirect("bms_login.php");
 }

 function BMS_write_error($m_error_message)
 {
    return "<span id=\"bms_error\">$m_error_message</span>";
 }
?>
