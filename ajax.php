<?php

require_once 'DB.php';
require_once 'Message.php';
require_once 'Utils.php';

Utils::isBanIP($_SERVER['REMOTE_ADDR']);

if (isset($_GET['action']))
    $action = $_GET['action'];
else
    $action = NULL;


if (!strcmp($action, "add"))
{
    
    if (isset($_POST['text']) && !empty($_POST['text']) && isset($_POST['name']) && !empty($_POST['name']))
    {
        if (Utils::checkNickName(strtolower($_POST['name'])))
        {
            echo "ERROR_1";
            exit;
        }
        
        $msg = new Message();
        $msg->setText(trim($_POST['text']));
        $msg->setName(trim($_POST['name']));
        
        $db = new DBQuery();
        $r = $db->addMessage($msg);
        echo $r;
        $db->close();
    }
    else
    {
        echo "ERROR_2";
    }
}
else if (!strcmp($action, "get"))
{
    if (isset($_POST['lastID']) && !empty($_POST['lastID']))
    {
        $db = new DBQuery();
        $msgs = $db->getMessages(intval($_POST['lastID']));
        $db->close();
        echo Utils::jsonMessages($msgs);
    }
}
else if (!strcmp($action, "lastID"))
{
   $db = new DBQuery();
   $lastID = $db->getLastID();
   $db->close();
   echo "{\"lastID\" : \"{$lastID}\"}";
}

?>