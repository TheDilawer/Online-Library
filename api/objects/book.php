<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Book
{
    private $conn;
    private $table_name="lib_book";

    public $id;
    public $ownerId;
    public $name;
    public $isbnNo;

    public function __construct($db)
    {
        $this->conn=$db;
    }

    //Create

    public function create()
    {
        //TODO
        //Check isbnNo for duplicate


        $query='INSERT INTO '.$this->table_name.' (ownerid,name,isbnNo) VALUES (:ownerid,:name,:isbnNo) ';
        $stmt=$this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->isbnNo=htmlspecialchars(strip_tags($this->isbnNo));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":isbnNo", $this->isbnNo);
        $stmt->bindParam(":ownerid", $this->ownerId);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return 1;
        }
        else
        {
            return 0;
        }

    }

    //Update

    public function update()
    {

    }

    //Delete

    public function delete()
    {

    }

    //Read - All

    public function readAll()
    {

    }

    //Read Single

    public function read()
    {

    }



}
