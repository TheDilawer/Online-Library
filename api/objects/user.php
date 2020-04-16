<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class User
{

    private $conn;
    private $table_name="lib_user";

    public $id;
    public $userName;
    public $password;
    public $role;
    public $resetToken;
    public $tokenExpire;
    public $hash;
    public $active;
    public $registerDate;
    public $token;

    //
    //Roles : 1 - User
    //Roles : 2031 - Admin



    public function __construct($db)
    {
        $this->conn=$db;
    }

    //Create
    // Returns
    // Code 0 - Fail
    // Code 1 - Success
    // Code 2 - Duplicated

    public function create()
    {

      //First Check For Duplicated Email
      if($this->checkMail())
      {
        $query='INSERT INTO '.$this->table_name.' (username,password,role,hash,active,register_date) VALUES (:userName,:password,:role,:hash,:active,:registerDate) ';
        $stmt=$this->conn->prepare($query);

        $this->userName=htmlspecialchars(strip_tags($this->userName));
        $this->password=htmlspecialchars(strip_tags($this->password));

        // Hash the password
        $this->password=password_hash($this->password, PASSWORD_DEFAULT);
        $this->registerDate=date("Y-m-d H:i:s");

        $stmt->bindParam(":userName", $this->userName);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":hash", $this->hash);
        $stmt->bindParam(":active", $this->active);
        $stmt->bindParam(":registerDate", $this->registerDate);

        if($stmt->execute()){

          $this->id = $this->conn->lastInsertId();
          return 1;
        }
        else
        {

          return 0;
        }
      }
      else
      {
        return 2;
      }

    }

    //Check for duplicated email address

    public function checkMail()
    {
        $query='SELECT username FROM '.$this->table_name.' WHERE username=:userName ';
        $stmt=$this->conn->prepare($query);

        $this->userName=htmlspecialchars(strip_tags($this->userName));
        $stmt->bindParam(":userName", $this->userName);

        if($stmt->execute()){

            if($stmt->rowCount()>0)
            {
                return false;
            }
            else
            {
                return true;
            }

        }
        else
        {
            return false;
        }
    }



    // Read All

    public function read()
    {
      $query='SELECT * FROM '.$this->table_name.' ';

      // Prepare Query
      $stmt=$this->conn->prepare($query);

      //Execute Query
      $stmt->execute();

      //Return Result
      return $stmt;
    }

    // Read Single

    public function readSingle()
    {
    }

    // Delete

    public function delete()
    {
    }

    // Update

    public function update()
    {
    }

    // Login

    public function login()
    {

      $query='SELECT id,username,password,role,active FROM '.$this->table_name.' WHERE username=:userName';

      // Prepare Query
      $stmt=$this->conn->prepare($query);


      $this->userName=htmlspecialchars(strip_tags($this->userName));
      $stmt->bindParam(":userName", $this->userName);

      //Execute Query
      $stmt->execute();


      //Return Result
      return $stmt;

    }

    // Save token to db
    public function assignToken()
    {
      $query='UPDATE '.$this->table_name.' SET token=:token WHERE id='.$this->id.' AND username=:userName ';
      // Prepare Query
      $stmt=$this->conn->prepare($query);

      $this->userName=htmlspecialchars(strip_tags($this->userName));
      $stmt->bindParam(":userName", $this->userName);
      $stmt->bindParam(":token", $this->token);

      //Execute Query
      $stmt->execute();
      //Return Result
      return $stmt;
    }

    //Get User Token Secret

    public function getUserToken()
    {
      $query='SELECT token,role FROM '.$this->table_name.' WHERE id='.$this->id.' AND username=:userName ';
      $stmt=$this->conn->prepare($query);

      $this->userName=htmlspecialchars(strip_tags($this->userName));
      $stmt->bindParam(":userName", $this->userName);
      $stmt->execute();
      //Return Result
      return $stmt;
    }




}
