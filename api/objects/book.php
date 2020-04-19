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
    public $location;
    public $writer;

    public function __construct($db)
    {
        $this->conn=$db;
    }

    //Create

    public function create()
    {
        //TODO
        //Check isbnNo for duplicate


        $query='INSERT INTO '.$this->table_name.' (ownerid,name,isbnNo,location) VALUES (:ownerid,:name,:isbnNo,:location) ';
        $stmt=$this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->isbnNo=htmlspecialchars(strip_tags($this->isbnNo));
        $this->location=htmlspecialchars(strip_tags($this->location));


        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":isbnNo", $this->isbnNo);
        $stmt->bindParam(":ownerid", $this->ownerId);
        $stmt->bindParam(":location", $this->location);


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
        $query='UPDATE '.$this->table_name.' SET name=:name,isbnNo=:isbnNo,location=:location WHERE ownerid=:ownerId AND id=:id';
        $stmt=$this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->isbnNo=htmlspecialchars(strip_tags($this->isbnNo));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->location=htmlspecialchars(strip_tags($this->location));


        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":isbnNo", $this->isbnNo);
        $stmt->bindParam(":ownerId", $this->ownerId);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":location", $this->location);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return 1;
        }
        else
        {
            return 0;
        }

    }

    //Delete

    public function delete()
    {

    }

    //Read - All

    public function readAll()
    {
        $query='SELECT * FROM '.$this->table_name.' WHERE ownerid=:ownerId ';

        // Prepare Query
        $stmt=$this->conn->prepare($query);



        $stmt->bindParam(":ownerId", $this->ownerId);

        //Execute Query
        $stmt->execute();

        //Return Result
        return $stmt;

    }

    //Read Single

    public function read()
    {
        $query='SELECT * FROM '.$this->table_name.' WHERE ownerid=:ownerId AND id=:bookId ';

        // Prepare Query
        $stmt=$this->conn->prepare($query);



        $stmt->bindParam(":ownerId", $this->ownerId);
        $stmt->bindParam(":bookId",$this->id);
        //Execute Query
        $stmt->execute();

        //Return Result
        return $stmt;

    }



}
