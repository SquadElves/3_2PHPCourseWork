<?php
class Auction
{
    public int $ID;
    public int $userID;
    public int $stateID;
    public string $title;
    public string $description;
    public DateTime $startDate;
    public DateTime $endDate;
    public int $startPrice;
    public int $buyNowPrice;
    public int $nowPrice;

    public int $bidID;
    public int $bidStep;
    public int $bidCount;

    public function __construct() {
        $arguments = func_get_args();
        $numberOfArguments = func_num_args();

        $constructor = method_exists(
            $this,
            $fn = "__construct" . $numberOfArguments
        );

        if ($constructor) {
            call_user_func_array([$this, $fn], $arguments);
        }
    }
    function placeBid(int $price, int $userID){
        global $pdo;
        $nowBidID = -1;
        $nowPrice = -1;
        if($this->isNotOver(new DateTime())){
            if($price >= $this->buyNowPrice and $this->buyNowPrice != -1) {
                $this->setOver();
                $price = $this->buyNowPrice;
                $nowPrice = $this->buyNowPrice;
            }else{
                if ($price < ($this->nowPrice + $this->bidStep))
                    throw new Exception("Bid price is to low");
                else{
                    $nowPrice = $this->nowPrice;
                    if(isset($this->bidID)){
                        $sql = "SELECT * FROM bid where ID = :ID";
                        $query = $pdo->prepare($sql);
                        $query->execute(['ID' => $this->bidID]);
                        $date = $query -> fetch();
                        if($price <= $date['amount']) {
                            $nowPrice = $price;
                            $nowBidID = $this->bidID;
                        }else
                            $nowPrice = $date['amount'];

                    }
                }
            }
            $sql = "Insert into bid (auctionid, userid, amount) values ($this->ID, $userID, :price)";
            $query = $pdo->prepare($sql);
            $query->execute(["price" => $price]);
            if(!isset($this->bidID) or $nowBidID != $this->bidID)
                $nowBidID = $pdo->lastInsertId();
            $sql = "update auction set nowprice = $nowPrice, bidid = $nowBidID, bidcount = $this->bidCount+1 where id = $this->ID";
            $query = $pdo->prepare($sql);
            $query->execute();
            $this->nowPrice = $nowPrice;
            $this->bidID = $nowBidID;
            $this->bidCount = $this->bidCount+1;
        }
        else{
            throw new Exception("Auction is over");
        }
    }
    function setOver(){
        global $pdo;
        $sql = "update auction set stateid = 1 where id = $this->ID";
        $query = $pdo->prepare($sql);
        $query->execute();
        $this->stateID = 1;
    }
    function isNotOver (DateTime $nowDate){
        if($this->endDate < $nowDate and $this->stateID == 0) {
            $this->setOver();
        }

        if($this->stateID == 1)
            return false;
        else
            return true;
    }
    public function __construct2(array $POST, int $userID): void{
        $this->title = $POST['title'];
        $this->description = $POST['description'];
        $this->bidStep = 500;
        $this->buyNowPrice = -1;
        $this->startPrice = floatval($POST['startPrice']) * 100;

        if(isset($POST['buyNowPrice']) and $POST['buyNowPrice'] != "") {
            $this->buyNowPrice = floatval($POST['buyNowPrice']) * 100;
            if($this->buyNowPrice < $this->startPrice)
                throw new Exception("Buy It Now Price lower than Starting Price");
        }

        $this->startDate = new DateTime();
        $endDate = DateTime::createFromFormat('Y-m-d\TH:i', $POST['endDate']);
        if($endDate <= $this->startDate)
            throw new Exception("Auction ending time is to low");

        if(isset($_SESSION['userID']))
            $this->userID = $_SESSION['userID'];
        else
            throw new Exception("You need to sign in to create an auction");
        global $pdo;
        $sql = "insert into Auction (userid, title, description, startDate, endDate,
                startPrice, nowPrice, buyNowPrice, bidstep) values
                (:userid, :title, :description, :startDate, :endDate,
                :startPrice, :nowPrice, :buyNowPrice, :bidstep)";
        $query = $pdo->prepare($sql);
        $query->execute(['userid' => $this->userID, 'title' => $this->title,
        'description' => $this->description,
        'startDate' => $this->startDate->format('Y-m-d H:i:s'),
        'endDate' => $endDate->format('Y-m-d H:i:s'),
        'startPrice' => $this->startPrice, 'nowPrice' => $this->startPrice, 'buyNowPrice' => $this->buyNowPrice,
        'bidstep' => $this->bidStep]);

        $this->ID = $pdo->lastInsertId();
    }
    public function __construct1(int $ID): void{
        $this->ID = $ID;

        global $pdo;
        $sql = "SELECT * FROM auction where ID = :ID";
        $query = $pdo->prepare($sql);
        $query->execute(['ID' => $ID]);
        $date = $query -> fetchAll();

        if(count($date) < 1)
            throw new Exception("There is no auction with this ID");

        echo "<br/>";

        $date = $date[0];
        $this->userID = $date['userid'];
        $this->stateID = $date['stateid'];
        $this->title = $date["title"];
        $this->description = $date["description"];
        $this->startDate = DateTime::createFromFormat('Y-m-d H:i:s', $date["startdate"]);
        $this->endDate = DateTime::createFromFormat('Y-m-d H:i:s', $date["enddate"]);
        $this->startPrice = $date["startprice"];
        $this->buyNowPrice = $date["buynowprice"];
        $this->nowPrice = $date["nowprice"];
        $this->bidCount = $date["bidcount"];
        $this->bidStep = $date['bidstep'];

        if($date["bidid"] != null) $this->bidID = $date["bidid"];
    }

}