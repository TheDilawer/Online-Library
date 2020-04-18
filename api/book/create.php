<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // get database connection
    include_once '../config/database.php';

    // instantiate user and book object
    include_once '../objects/book.php';
    include_once '../objects/user.php';

    // Get Funtions
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

    $jwt='';

    // Create Object
    $book=new Book($db);




    //Read Cookie

    $jwt=$_COOKIE['JWT'];

    if($jwt)
    {
        // Get User Inputs
        $book->name=isset($_GET['name']) ? $_GET['name'] : '';
        $book->isbnNo=isset($_GET['isbnNo']) ? $_GET['isbnNo'] : '';
        $book->location=isset($_GET['locationBox']) ? $_GET['locationBox'] : '';

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

        $response_arr=array();

        //TODO
        //Check user role form db and token role data

        try {

            $decoded = JWT::decode($jwt, $userSecret, array('HS256'));

            // Access is granted. Add code of the operation here

            //Owner id
            $book->ownerId=$user->id;


            if($book->create())
            {
                $response_arr=array(
                    "status"=>"success",
                    "message"=>"Book created successfully.",
                    "BookId"=>$book->id,
                    "isbnNo"=>$book->isbnNo,
                );

            }
            else
            {
                $response_arr=array(
                    "status"=>"fail",
                    "message"=>"There is a problem no book created."
                );
            }
            echo json_encode($response_arr);

        }
        catch (Exception $e)
        {

            http_response_code(401);
            echo json_encode(array("message"=>"Erişim reddedildi."));
        }
    }
    else
    {
        echo json_encode(array("message"=>"No token No data."));
    }

