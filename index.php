<?php

require_once 'Utils.php';

Utils::isBanIP($_SERVER['REMOTE_ADDR']);

?>
<!-- Указываем DOCTYPE -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>ajachat</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">


<link rel="stylesheet" type="text/css" media="screen" href="style/style.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<link rel="SHORTCUT ICON" href="img/icon.ico"type="image/x-icon" />
<script type="text/javascript">
    lastID = 0;
    
    function Start()
    {
        json = $.ajax("ajax.php?action=lastID").done(function(r) { 
            id = $.parseJSON(r);
            lastID = id.lastID - 5;
        });
    }
    
    function Load()
    {
        $.post("ajax.php?action=get", {"lastID" : lastID}).done(function (r){
            msgs = $.parseJSON(r);
            
            for (var i in msgs)
            {
                msg = msgs[i];
                html = "<div class='message'>";
                html += "<p class='msg_header'><b>" + msg.name + "</b> (" + msg.datetime + "):</p>";
                html += "<p>" + msg.text + "</p>";
                html += "</div>";
                
                $("#div_chat").append(html);
            }
            
            lastID = msgs[msgs.length - 1].id;
        });
    }
    
    function Send()
    {
        data = $("#frmmain").serialize();
        
        $r = $.post("ajax.php?action=add", data).done(function (r){
           
            if (r == "ERROR_1")
            {
                html = "<div class='message'><p style='color: red;  font-weight: bold;'>";
                html += "This name is banned!";
                html += "</p></div>";
                $("#div_chat").append(html);
                
            }
            else if (r == "ERROR_2")
            {    
                html = "<div class='message'><p style='color: red;  font-weight: bold;'>";
                html += "You have specified not all of the data for message!";
                html += "</p></div>";
                $("#div_chat").append(html);
            }
            else
            {
                $("#txt_message").val("");
                $("#txt_message").focus();
            }
        });
        
        return false;
    }
    
    $(document).ready(function() {
        $("#frmmain").submit(Send);
        $("#txt_message").focus();
        setInterval("Load();", 2000);
    
        Start(); 
    });
</script>
<body>
    <div id="header">
        <center><a href="#"><img src="img/ajachat.png" alt="logo" title="ajachat" /></a></center>
    </div>
    
    <div id="content">
        <center>
        <div id="div_chat" style="width: 500px; overflow: auto;">
        </div>
            
        <form id="frmmain" name="frmmain" action="">
               
            <input type="text" maxlength="25" value="Name" onfocus="this.value=''"  class="input_txt" id="name" name="name" style="font-weight: bold; width: 80px;" />
            <input type="text" maxlength="250" value="Message" onfocus="this.value=''" class="input_txt" id="txt_message" name="text" style="margin-left: 15px; width: 300px;" />
                <input type="submit" name="btn_send_chat" id="btn_send_chat" value="SEND" />
        </form>
        </center>
    </div>
</body>
</html>