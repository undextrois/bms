<?
  define (BMS_CODE_NO_ERROR,1);
  define (BMS_CODE_ERROR,0);
  define (BMS_CODE_CPUID,-1);
  define (BMS_CODE_MODULE,-2);
  define (BMS_CODE_COMBINED,-3);
  define (BMS_CODE_DIVIDE,-4);
  define (BMS_CODE_HL_ID,-5);

  /* String Conversion Table */

  $BMS_sct = array(
     0 => "LzSVaCxYMyTBbvWwAUtZcrusdX",   /* From A - Z */
     1 => "OnE2g9mKF3JQh84PGI5iHk6j71",   /* From a - z */
     2 => "RNpD0qeo1f"                    /* From 0 - 9 */
  );

  $BMS_code_compare = array(); /* loaded code compare */

  /* Generate Request Code - PC License */
  function BMS_request_code_PC($m_cpuid,$m_module_code)
  {
     $m_cpuid = trim($m_cpuid);     
     if ($m_cpuid == "") return BMS_CODE_ERROR;

     $m_module_code = trim($m_module_code);
     $m_length = strlen($m_module_code);

     /* CPUID specified */
     if (!preg_match("/^[0-9A-F]{16}$/sm",$m_cpuid,$m_map))
         return BMS_CODE_CPUID;

     /* Module ID specified */
     if (!preg_match("/^[0-9]{12}$/sm",$m_code,$m_map))
         return BMS_CODE_MODULE;

     /* Combined CPUID and Module ID specified */
     if (preg_match("/^[0-9A-F]{16}[0-9]{12}$/sm",$m_code,$m_map))
         return BMS_CODE_COMBINED;

     /* Divide by 4 digits */
     if (preg_match("/^[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9{4}-[0-9]{4}-[0-9]{4}$/sm",$m_code,$m_map))
        return BMS_CODE_DIVIDE;

     return BMS_CODE_NO_ERROR;
  }

  function BMS_generate_code_PC($m_code,$m_code_compare)
  {
     global $BMS_sct;

     $m_cpuid = "";
     $m_module_code = "";
     $m_new_cpuid = "";
     $m_has_cpuid = false;
     $m_new_code = "";
     $m_activation_code = "";

     $m_cpuid = substr($m_code,0,16);
     $m_module_code = substr($m_code,16);
     $m_hdas_cpuid = true;

     /* Translate Processor ID */
     $m_index = 0;
     $m_length = strlen($m_cpuid);
     while ($m_index < $m_length)
     {
        if ($m_cpuid{$m_index} >= 'A' && $m_cpuid{$m_index} <= 'Z')
            $m_code = $BMS_sct[0]{ord($m_cpuid{$m_index}) - ord('A')};
        else if ($m_cpuid{$m_index} >= 'a' && $m_cpuid{$m_index} <= 'z')
            $m_code = $BMS_sct[1]{ord($m_cpuid{$m_index}) - ord('a')};
        else if ($m_cpuid{$m_index} >= '0' && $m_cpuid{$m_index} <= '9')
            $m_code = $BMS_sct[2]{ord($m_cpuid{$m_index}) - ord('0')};

        // $m_code_compare = BMS_get_code_compare($m_module_code); /* get code compare from the table based on request module code*/

        $m_new_code = ord($m_code) + $m_code_compare;
        if ($m_index > 0) $m_activation_code .= "-";
        $m_activation_code .= $m_new_code;

        $m_index++;
     }

     return $m_activation_code;
  }

  function BMS_get_code_compare($m_module_code)
  {
     if ($m_module_code == "") return "";
     else return $m_code_compare[$m_module_code];
  }

  /* Generate Request Code - Hard Lock License */
  function BMS_generate_code_HL($m_code,$m_code_compare,$m_mac)
  {
     $m_codes = split("-",$m_code);
     $m_hlid = "";
     $m_module_code = "";

     if (count ($m_codes) == 2)
     {
         $m_hlid = $m_codes[0];
         $m_module_code = $m_codes[1];
     }

     if ($m_hlid > $m_code_compare) {
         while ($m_hlid > $m_code_compare) $m_hlid-=75;
     }

     if ($m_hlid < 50) {
         while ($m_hlid < 50) $m_hlid+=75;
     }

     $m_length = ((strlen($m_mac) / 2) + 1);
     $m_new_code = sprintf("%0" . $m_length . "d",$m_code_compare - $m_hlid);
     $m_len = 0;
     $m_len2 = 0;
     $m_length = strlen($m_mac);

     $m_activation_code = "";
     $m_code_combined = "";

     while ($m_len2 < $m_length)
     {
         if ($m_len) $m_code_combined .= "-";
         $m_code_combined.= $m_mac{$m_len2} . $m_new_code{$m_len};
         $m_len++;
         $m_len2+=2;
     }

     $m_verfication_code = "";
     $m_index = 0;
     $m_length = strlen($m_code_combined);
     while ($m_index < $m_length)
     {
        $m_verification_code.= ord($m_code_combined{$m_index});
        $m_index++;
     }

     $m_activation_code = $m_code_combined . "-" . $m_verification_code;

     return $m_activation_code;
  }
?>
