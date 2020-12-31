<html>
   <head>
   <style type="text/css">
   #bms_heading
   {
      font-family: verdana;
      font-size: 14px;
      font-weight: bold;
   }
   #bms_label
   {
      font-family: verdana;
      font-size: 10px;
      font-weight: bold;
   }
   #bms_input
   {
      font-family: verdana;
      font-size: 10px;
   }
   #bms_background_dark
   {
      background-color: #4f81bd;
   }
   #bms_background_light
   {      
      background-color: #d0d8e8;
   }
   #bms_error
   {
      font-family: verdana;
      font-size: 10px;
      font-weight: bold;
      color: #800000;
   }
   </style>
   <script language="javascript">
   function changeForm(id)
   {
      var m_form = document.forms[0];
      if (id == 1)
      {
         m_form.type.value="RQ";
         m_form.user.value = "";
         m_form.pass.value = "";
      }
      else if (id == 2)
      {
         m_form.type.value = "LG";
         m_form.code.value = "";
      }
   }
   </script>
   </head>
   <body>
   <div align="center">
   <table border="0" cellspacing="0" cellpadding="4">
   <tr id="bms_heading">
      <td id="bms_background_light">BMS Activation Main Page</td>
   </tr>
   <tr>
      <td>
      <?
         if (isset($m_error_message) && $m_error_message != "")
             print $m_error_message;
      ?>
      <form action="bms_login.php" method="POST">
      <table border="0" cellspacing="0" cellpadding="4">
      <tr id="bms_background_dark">
         <td id="bms_label">
         For End User - Please Enter your request code:<br>
         (user can enter any request code or cpu id).
         </td>
         <td><input type="text" name="code" id="bms_input" onFocus="changeForm(1);"></td>
      </tr>
      <tr id="bms_background_dark">
         <td colspan="2">&nbsp;</td>
      </td>
      <tr id="bms_background_dark">
         <td id="bms_label">For Admin:</td>
         <td><input type="text" name="user" maxlength="16" id="bms_input" onFocus="changeForm(2)"></td>
      </tr>
      <tr id="bms_background_dark">
         <td id="bms_label">&nbsp;</td>
         <td><input type="password" name="pass" maxlength="16" id="bms_input" onFocus="changeForm(2)"></td>
      </tr>
      <tr id="bms_background_light">
         <td id="bms_label">&nbsp;</td>
         <td><input type="submit" name="btn" value="Submit" id="bms_input"></td>
      </tr>
      </table>
      <input type="hidden" name="type" value="LG">
      </form>
      </td>
   </tr>
   </table>
   </div>
   </body>
</html>
