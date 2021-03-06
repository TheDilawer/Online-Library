<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // get database connection
    include_once '../config/database.php';

    // instantiate user and book object
    include_once '../objects/book.php';
    include_once '../objects/user.php';
    include_once '../objects/bookwriter.php';
    include_once '../objects/booktranslator.php';

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
    $book_writer=new BookWriter($db);
    $book_translator=new BookTranslator($db);




    //Read Cookie

    $jwt=$_COOKIE['JWT'];

    if($jwt)
    {
        // Get User Inputs
        $book->number=isset($_GET['number']) ? $_GET['number'] : '';
        $book->name=isset($_GET['name']) ? $_GET['name'] : '';
        $book->nameTitle=isset($_GET['nameTitle']) ? $_GET['nameTitle'] : '';

        $writerArray=isset($_GET['writerBox']) ?  $_GET["writerBox"] : '';
        $translatorArray=isset($_GET['translator']) ? $_GET['translator'] : '';

        $book->publisher=isset($_GET['publisherBox']) ?  $_GET["publisherBox"] : '';
        $book->publisherSeriesId=isset($_GET['publisherSeriesId']) ? $_GET['publisherSeriesId'] : '';
        $book->publisherSeriesNo=isset($_GET['publisherSeriesNo']) ? $_GET['publisherSeriesNo'] : '';
        $book->printingCount=isset($_GET['printingCount']) ? $_GET['printingCount'] : '';
        $book->printingDate=isset($_GET['printingDate']) ? $_GET['printingDate'] : '';
        $book->isbnNo=isset($_GET['isbnNo']) ? $_GET['isbnNo'] : '';
        $book->originalName=isset($_GET['originalName']) ? $_GET['originalName'] : '';
        $book->originalPublisher=isset($_GET['originalPublisher']) ? $_GET['originalPublisher'] : '';
        $book->originalLang=isset($_GET['originalLang']) ? $_GET['originalLang'] : '';
        $book->pageCount=isset($_GET['pageCount']) ? $_GET['pageCount'] : '';
        $book->buyDate=isset($_GET['buyDate']) ? $_GET['buyDate'] : '';
        $book->buyPrice=isset($_GET['buyPrice']) ? $_GET['buyPrice'] : '';
        $book->star=isset($_GET['star']) ? $_GET['star'] : '';
        $book->location=isset($_GET['locationBox']) ? $_GET['locationBox'] : '';
        $book->category=isset($_GET['category']) ? $_GET['category'] : '';
        $book->publisherSeries=isset($_GET['publisherSeries']) ? $_GET['publisherSeries'] : '';
        $book->firstPrintingDate=isset($_GET['firstPrintingDate']) ? $_GET['firstPrintingDate'] : '';



        $tempDate=$book->buyDate;
        $date = strtotime($tempDate);
        $book->buyDate= date("Y-m-d", $date);

        $tempDate1=$book->printingDate;
        $date2 = strtotime($tempDate1);
        $book->printingDate= date("Y-m-d", $date2);

        $tempDate2=$book->firstPrintingDate;
        $date3 = strtotime($tempDate1);
        $book->firstPrintingDate= date("Y-m-d", $date3);




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
                $book_writer->bookId=$book->id;
                $book_translator->bookId=$book->id;
                foreach ($writerArray as $writer)
                {
                    if($writer!=-1)
                    {
                        $book_writer->writerId=$writer;
                        $book_writer->create();
                    }

                }
                foreach ($translatorArray as $translator)
                {
                    if($translator!=-1)
                    {
                    $book_translator->translatorId=$translator;
                    $book_translator->create();
                    }
                }
                $response_arr=array(
                    "status"=>"success",
                    "message"=>"Book created successfully.",
                    "BookId"=>$book->id,
                    "isbnNo"=>$book->isbnNo
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

