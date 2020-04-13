<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // get database connection
    include_once '../config/database.php';

    // instantiate user object
    include_once '../objects/user.php';

    // instantiate user object
    include '../functions.php';

    //JWT
    require_once '../../JWT/BeforeValidException.php';
    require_once '../../JWT/ExpiredException.php';
    require_once '../../JWT/SignatureInvalidException.php';
    require_once '../../JWT/JWT.php';

    use \Firebase\JWT\JWT;


// adjust headers
    header("Access-Control-Allow-Origin: * ");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    // Get Database Connection
    $database = new Database();
    $db = $database->getConnection();

    //Define Variables to Use
    $id ='';
    $username ='';
    $role='';
    $jwt='';
    $secret_key = '';



    //Read Header
    foreach (getallheaders() as $name => $value) {
     if($name=="JWT")
        {
          $jwt=$value;
        }
    }


    // Read JWT Payload
    $unDecodedJWT=getJwtPayload($jwt);


    // Create User Object
    $user=new User($db);
    $user->id=$unDecodedJWT->data->id;
    $user->userName=$unDecodedJWT->data->username;

    //Get User Secret From Db
    $tokenFromDb=$user->getUserToken();
    $row = $tokenFromDb->fetch(PDO::FETCH_ASSOC);

    //Assign secret and role for check
    $userSecret=$row['token'];
    $userRole=$row['role'];


    if($jwt)
    {
      if($userRole==2031)
      {
        try {

          $decoded = JWT::decode($jwt, $userSecret, array('HS256'));

          // Access is granted. Add code of the operation here

          // Create User Array
          $user_arr=array();
          $user_arr["user"]=array();


          // Read All Users
          $stmt=$user->read();

          //Get the row count
          $num = $stmt->rowCount();


          if($num>0)
          {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
              extract($row);
              $user_item=array(
                "id" => $id,
                "username" => $username,
                "role"=> $role,

              );
              array_push($user_arr["user"], $user_item);
            }
            echo json_encode($user_arr["user"],JSON_PRETTY_PRINT);
          }
          else
          {
            echo json_encode(array("message"=>"Hiç Kullanıcı Bulunamadı."));
          }


        }
        catch (Exception $e)
        {

          http_response_code(401);
          echo json_encode(array("message"=>"Erişim reddedildi."));
        }
      }
      else
      {
        echo json_encode(array("message"=>"Kullanıcının buraya erişme yetkisi bulunmamaktadır."));

      }
      }
    else
    {
      echo json_encode(array("message"=>"No token No data."));
    }




