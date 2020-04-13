<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // get database connection
    include_once '../config/database.php';

    // instantiate user object
    include_once '../objects/user.php';

    // Get Funtions
    include '../functions.php';

    // adjust headers
    header("Access-Control-Allow-Origin: * ");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    // Get Database Connection
    $database = new Database();
    $db = $database->getConnection();

    // Create Object
    $user=new User($db);

    $user_arr=array();

    //Get User input
    $user->userName=isset($_GET['username']) ? $_GET['username'] : '';
    $user->password=isset($_GET['password']) ? $_GET['password'] : '';
    $user->role=1;
    $user->active=0;
    $user->hash=getToken(32);


    // Create User
    // Response 0 - Fail
    // Response 1 - Success
    // Response 2 - Duplicate
    $response=$user->create();
    if($response==0)
    {
      $user_arr=array(
        "status"=>false,
        "message"=>"Kayıt oluşturulken bir problem oluştu."
      );
    }
    else if($response==1)
    {
      $user_arr=array(
        "status"=>true,
        "message"=>"Başarı ile kullanıcı kaydı oluşturulmuştur.",
        "Userid"=>$user->id,
        "UserName"=>$user->userName,
      );
    }
    else
    {
      $user_arr=array(
        "status"=>false,
        "message"=>"Bu mail ile daha önce kayıt oluşturulmuş."
      );
    }


    echo json_encode($user_arr);
