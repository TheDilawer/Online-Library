<?php
//Errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../objects/token.php';
//
//require_once '../../JWT/BeforeValidException.php';
//require_once '../../JWT/ExpiredException.php';
//require_once '../../JWT/SignatureInvalidException.php';
//require_once '../../JWT/JWT.php';
//
//use \Firebase\JWT\JWT;

include '../functions.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");




// get database connection
$database = new Database();
$db = $database->getConnection();

//$key="8c1b6428-d8bf-4e01-b021-a65edf0b675e";

// prepare user object
$user = new User($db);
// set ID property of user to be edited
$user->userName = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$user->password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';


// read the details of user to be edited
$stmt = $user->login();
$user_arr='';

    if($stmt->rowCount() > 0)
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $passwordInDb=$row['password'];
        $passwordUserEntry=$user->password;
       if(password_verify($passwordUserEntry,$passwordInDb))
       {
         $genericSecretKey=getToken(64);
         $token=new Token($genericSecretKey);
         $user->token=$genericSecretKey;
         $user->id=$row['id'];
         $user->assignToken();

         $jwt=$token->generateJwtToken($row['id'],$row['username'],$row['role']);


         echo json_encode(
           array(
              "status"=>"success",
             "message" => "Successful login.",
             "jwt" => $jwt,
             "username" =>  $row['username'],
             "id"=>$row['id']
           ));
         // unset all cookies
         if (isset($_SERVER['HTTP_COOKIE'])) {
           $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
           foreach($cookies as $cookie) {
             $parts = explode('=', $cookie);
             $name = trim($parts[0]);
             setcookie($name, '', time()-1000);
             setcookie($name, '', time()-1000, '/');
           }
         }
         // set JWT cookies
           http_response_code(200);
         setcookie("JWT", $jwt, time()+60*15*15*8, '/', '/', 1, 1);


       }
       else
       {
         $user_arr=array(
           "status"=>"error",
           "message" => "Hatalı yada eksik şifre.",
         );
           http_response_code(200);
         print_r(json_encode($user_arr,JSON_UNESCAPED_UNICODE));

       }


    }
    else
    {
        $user_arr=array(
            "status"=>"error",
            "message" => "Sistemde Kayıtlı Böyle Bir Kullanıcı Bulunamadı.",
        );
        http_response_code(200);
        print_r(json_encode($user_arr,JSON_UNESCAPED_UNICODE));
    }


