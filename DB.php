<?php

require_once 'config.php';
require_once 'Message.php';


class DBQuery
{
    protected $pdo;
    protected $isConnected;
    
    public function __construct($host = DB_HOST, $port = DB_PORT, $user = DB_USER, $pass = DB_PASS, $name = DB_NAME) {
        $this->pdo = new PDO("mysql:host={$host}:{$port};dbname={$name}", $user, $pass);
        
        if (!$this->pdo)
        {
            throw new DBException("Cannot connect to database!");
        }
        else
            $this->isConnected = TRUE;
        
        $this->pdo->exec("SET NAMES 'utf8'");
    }
    
    public function checkIP($ip)
    {
        if (!$this->isConnected)
            return FALSE;
        
        $sql = "SELECT COUNT(*) FROM banip where ip = :ip";
        $pr = $this->pdo->prepare($sql);
        $pr->bindValue(":ip", $ip);
        $pr->execute();
        
        $o = $pr->fetch(); 
        $count = intval($o[0]);
        
        if ($count > 0)
            return TRUE;
        else
            return FALSE;
    }
            
    public function isConnected() { return $this->isConnected; }
    
    public function getLastID()
    {
        if (!$this->isConnected)
            return array();
        
       $sql = "SELECT id FROM message ORDER BY id DESC LIMIT 1";
       $pr = $this->pdo->prepare($sql);
       $pr->execute();
       
       $o = $pr->fetch();
       
       if (empty($o))
           return -1;
       else
           return $o['id'];
    }
    
    public function getMessages($last)
    {
        if (!$this->isConnected)
            return array();
        
        if (empty($last))
            $last = 0;
        
        $sql = "SELECT id, name, text, date_format(datetime, '%h:%i:%s %d/%m/%Y') as datetime FROM message WHERE id > :last LIMIT 5";
        $pr = $this->pdo->prepare($sql);
        $pr->bindValue(":last", $last);
        $pr->execute();
        
        $result = array();
        while ($o = $pr->fetch())
        {
            $result[] = new Message($o['id'], htmlspecialchars($o['name']), htmlspecialchars($o['text']), $o['datetime']);
        }
        
        return $result;
    }
    
    public function addMessage(Message $msg)
    {
        if (!$this->isConnected)
            return FALSE;
       
        $name = $msg->getName();
        $text = $msg->getText();
        $name = htmlspecialchars($name);
        $text = htmlspecialchars($text);
        
        if (empty($text) || empty($name))
            return "ERROR_2";

        
        $name = substr($msg->getName(), 0, 25);
        $text = substr($msg->getText(), 0, 250);
        
        $sql = "INSERT INTO message(name, text) VALUES(:name, :text)";
        $pr = $this->pdo->prepare($sql);
        $pr->bindValue(":name", $name);
        $pr->bindValue(":text", $text);
        
        $e = $pr->execute();
        return $e;
    }
    
    public function close()
    {
        $this->pdo = NULL;
    }
}

class DBException extends Exception { }

?>
