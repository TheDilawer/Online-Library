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
    public $number;
    public $name;
    public $nameTitle;
    public $publisher;
    public $publisherSeriesId;
    public $publisherSeriesNo;
    public $category;
    public $printingCount;
    public $printingDate;
    public $isbnNo;
    public $originalName;
    public $originalPublisher;
    public $translator;
    public $originalLang;
    public $pageCount;
    public $buyDate;
    public $buyPrice;
    public $location;
    public $star;
    public $createDate;
    public $publisherSeries;
    public $firstPrintingDate;



    public function __construct($db)
    {
        $this->conn=$db;
    }

    //Create

    public function create()
    {
        //TODO
        //Check isbnNo for duplicate


        $query='INSERT INTO '.$this->table_name.' 
        (ownerid,name,isbnNo,location,publisher,number,nameTitle,publisherSeriesId,
        publisherSeriesNo,category,printingCount,printingDate,originalName,originalPublisher,
        originalLang,pageCount,buyDate,buyPrice,star,createDate,publisherseries,translator,firstPrintingDate)
         VALUES (:ownerid,:name,:isbnNo,:location,:publisher,:number,:nameTitle,:publisherSeriesId,
        :publisherSeriesNo,:category,:printingCount,:printingDate,:originalName,:originalPublisher,
        :originalLang,:pageCount,:buyDate,:buyPrice,:star,:createDate,:publisherSeries,:translator,:firstPrintingDate) ';
        $stmt=$this->conn->prepare($query);

        $this->createDate=date("Y-m-d H:i:s");

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->isbnNo=htmlspecialchars(strip_tags($this->isbnNo));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->publisher=htmlspecialchars(strip_tags($this->publisher));
        $this->number=htmlspecialchars(strip_tags($this->number));
        $this->nameTitle=htmlspecialchars(strip_tags($this->nameTitle));
        $this->publisherSeriesId=htmlspecialchars(strip_tags($this->publisherSeriesId));
        $this->publisherSeriesNo=htmlspecialchars(strip_tags($this->publisherSeriesNo));
        $this->category=htmlspecialchars(strip_tags($this->category));
        $this->printingCount=htmlspecialchars(strip_tags($this->printingCount));
        $this->printingDate=htmlspecialchars(strip_tags($this->printingDate));
        $this->originalName=htmlspecialchars(strip_tags($this->originalName));
        $this->originalPublisher=htmlspecialchars(strip_tags($this->originalPublisher));
        $this->originalLang=htmlspecialchars(strip_tags($this->originalLang));
        $this->buyDate=htmlspecialchars(strip_tags($this->buyDate));
        $this->buyPrice=htmlspecialchars(strip_tags($this->buyPrice));
        $this->star=htmlspecialchars(strip_tags($this->star));
        $this->publisherSeries=htmlspecialchars(strip_tags($this->publisherSeries));
        $this->translator=htmlspecialchars(strip_tags($this->translator));
        $this->firstPrintingDate=htmlspecialchars(strip_tags($this->firstPrintingDate));



        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":isbnNo", $this->isbnNo);
        $stmt->bindParam(":ownerid", $this->ownerId);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":publisher", $this->publisher);
        $stmt->bindParam(":nameTitle", $this->nameTitle);
        $stmt->bindParam(":number", $this->number);
        $stmt->bindParam(":publisherSeriesId", $this->publisherSeriesId);
        $stmt->bindParam(":publisherSeriesNo", $this->publisherSeriesNo);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":printingCount", $this->printingCount);
        $stmt->bindParam(":printingDate", $this->printingDate);
        $stmt->bindParam(":originalName", $this->originalName);
        $stmt->bindParam(":originalPublisher", $this->originalPublisher);
        $stmt->bindParam(":originalLang", $this->originalLang);
        $stmt->bindParam(":pageCount", $this->pageCount);
        $stmt->bindParam(":buyDate", $this->buyDate);
        $stmt->bindParam(":buyPrice", $this->buyPrice);
        $stmt->bindParam(":star", $this->star);
        $stmt->bindParam(":createDate", $this->createDate);
        $stmt->bindParam(":publisherSeries", $this->publisherSeries);
        $stmt->bindParam(":translator", $this->translator);
        $stmt->bindParam(":firstPrintingDate", $this->firstPrintingDate);



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
        $query='UPDATE '.$this->table_name.' SET 
        name=:name,
        isbnNo=:isbnNo,
        location=:location,
        publisher=:publisher,
        number=:number,
        nameTitle=:nameTitle,
        publisherSeriesId=:publisherSeriesId,
        publisherSeriesNo=:publisherSeriesNo,
        category=:category,
        printingCount=:printingCount,
        printingDate=:printingDate,
        originalName=:originalName,
        originalPublisher=:originalPublisher,
        originalLang=:originalLang,
        pageCount=:pageCount,
        buyDate=:buyDate,
        buyPrice=:buyPrice,
        star=:star,
        publisherseries=:publisherSeries,
        translator=:translator,
        firstPrintingDate=:firstPrintingDate
        WHERE ownerid=:ownerId AND id=:id';
        $stmt=$this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->isbnNo=htmlspecialchars(strip_tags($this->isbnNo));
        $this->location=htmlspecialchars(strip_tags($this->location));
        $this->publisher=htmlspecialchars(strip_tags($this->publisher));
        $this->number=htmlspecialchars(strip_tags($this->number));
        $this->nameTitle=htmlspecialchars(strip_tags($this->nameTitle));
        $this->publisherSeriesId=htmlspecialchars(strip_tags($this->publisherSeriesId));
        $this->publisherSeriesNo=htmlspecialchars(strip_tags($this->publisherSeriesNo));
        $this->category=htmlspecialchars(strip_tags($this->category));
        $this->printingCount=htmlspecialchars(strip_tags($this->printingCount));
        $this->printingDate=htmlspecialchars(strip_tags($this->printingDate));
        $this->originalName=htmlspecialchars(strip_tags($this->originalName));
        $this->originalPublisher=htmlspecialchars(strip_tags($this->originalPublisher));
        $this->originalLang=htmlspecialchars(strip_tags($this->originalLang));
        $this->buyDate=htmlspecialchars(strip_tags($this->buyDate));
        $this->buyPrice=htmlspecialchars(strip_tags($this->buyPrice));
        $this->star=htmlspecialchars(strip_tags($this->star));
        $this->translator=htmlspecialchars(strip_tags($this->translator));
        $this->firstPrintingDate=htmlspecialchars(strip_tags($this->firstPrintingDate));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":isbnNo", $this->isbnNo);
        $stmt->bindParam(":ownerId", $this->ownerId);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":publisher", $this->publisher);
        $stmt->bindParam(":nameTitle", $this->nameTitle);
        $stmt->bindParam(":number", $this->number);
        $stmt->bindParam(":publisherSeriesId", $this->publisherSeriesId);
        $stmt->bindParam(":publisherSeriesNo", $this->publisherSeriesNo);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":printingCount", $this->printingCount);
        $stmt->bindParam(":printingDate", $this->printingDate);
        $stmt->bindParam(":originalName", $this->originalName);
        $stmt->bindParam(":originalPublisher", $this->originalPublisher);
        $stmt->bindParam(":originalLang", $this->originalLang);
        $stmt->bindParam(":pageCount", $this->pageCount);
        $stmt->bindParam(":buyDate", $this->buyDate);
        $stmt->bindParam(":buyPrice", $this->buyPrice);
        $stmt->bindParam(":star", $this->star);
        $stmt->bindParam(":publisherSeries", $this->publisherSeries);
        $stmt->bindParam(":translator", $this->translator);
        $stmt->bindParam(":firstPrintingDate", $this->firstPrintingDate);

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

    //Get Last Book Number
    // Other then id every user can have own book number system
    // If user want to index them differently we will give another id

    public function getLastBookNumber()
    {
        $query='SELECT MAX(number) as number FROM '.$this->table_name.' WHERE ownerid=:ownerId ';

        // Prepare Query
        $stmt=$this->conn->prepare($query);



        $stmt->bindParam(":ownerId", $this->ownerId);
        //Execute Query
        $stmt->execute();

        //Return Result
        return $stmt;

    }



}
