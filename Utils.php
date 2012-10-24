<?php

require_once 'Message.php';
require_once 'DB.php';

class Utils {
    static public function checkNickName($name)
    {
        $f = fopen("rnames.txt", "r");

        if (!$f)
            return FALSE;
        
        $find = FALSE;
        while (!feof($f))
        {
            $a = fscanf($f, "%s\n");
            
            if (!strcmp($a[0], $name))
            {
                $find = TRUE;
                break;
            }
        }
        
        fclose($f);
        
        return $find;
    }
    
    static public function jsonMessages($msgs)
    {
        $json = "[";
      
        
        $c = "";
        foreach ($msgs as $msg)
        {
            $json .= "$c{";
            $json .="\"id\": \"{$msg->getID()}\",";
            $json .= "\"text\": \"{$msg->getText()}\",";
            $json .= "\"name\": \"{$msg->getName()}\",";
            $json .= "\"datetime\": \"{$msg->getDateTime()}\"";
            $json .= "}";
            $c = ",";
        }
        
        $json .= "]";
        return $json;
    }
    
    static public function isBanIP($ip)
    {
        $db = new DBQuery();
        if ($db->checkIP($ip))
        {
            header("Location: ban.html");
        }
    }
}

?>
