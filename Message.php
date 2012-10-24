<?php

class Message {
    protected $id;
    protected $name;
    protected $text;
    protected $datetime;
    
    public function __construct($id = NULL, $name = NULL, $text = NULL, $datetime = NULL) {
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->datetime = $datetime;
    }
    
    public function getID() { return $this->id; }
    public function setID($id) { $this->id = $id; }
    
    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
    
    public function getText() { return $this->text; }
    public function setText($text) { $this->text = $text; }
    
    public function getDateTime() { return $this->datetime; }
    public function setDateTime($datetime) { $this->datetime = $datetime; }
}

?>
