<?php
    require "app/include/header.php";
    require "app/classes/Auction.php";
?>

<?php
    if(isset($_GET['id'])) {
        try {
            require "app/database/ConnectDB.php";
            $auction = new Auction($_GET['id']);
        }catch (Exception $exception){
            echo "<h1>". $exception->getMessage() . "<h1/>";
        }
    }
?>

<?php
    try {
        if (isset($_POST['btnBid']) and isset($_POST['price'])) {
            require_once "app/database/ConnectDB.php";
            $auction->placeBid(floatval($_POST['price']) * 100, $_SESSION['userID']);
            $_POST = [];
        } else if (isset($_POST['btnBIN'])) {
            require_once "app/database/ConnectDB.php";
            $auction->placeBid($auction->buyNowPrice, $_SESSION['userID']);
            $_POST = [];
        }
    } catch (Exception $exception){
        $_POST = [];
        echo $exception->getMessage();
    }
?>

<!DOCTYPE html>
<html lang=""><body>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .bid-form {
            margin-top: 20px;
        }
    </style>
    <?php if(isset($auction)):?>
    <h1>Online Auction - Item Details</h1>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Ending</th>
            <th>Amount of bids</th>
            <th>Starting Price</th>
            <th>Current Highest Bid</th>
            <?php if($auction->buyNowPrice > -1)
                echo "<th>Buy It Now Price</th>"?>
        </tr>
        <tr>
            <td><?php echo $auction->title?></td>
            <td><?php echo $auction->description?></td>
            <td><?php echo $auction->endDate->format('Y-m-d H:i:s')?></td>
            <td><?php echo $auction->bidCount?></td>
            <td><?php echo $auction->startPrice / 100?></td>
            <td><?php echo $auction->nowPrice / 100?></td>
            <?php if($auction->buyNowPrice > -1)
                echo "<th>". $auction->buyNowPrice / 100 ."</th>"?>
        </tr>
    </table>
    <?php
        require_once "app/database/ConnectDB.php";
        if($auction->isNotOver(new DateTime())):?>
        <div class="bid-form">
            <h2>Place Your Bid</h2>
            <form action="" method="post">
                <label for="price">Bid Amount:</label>
                <input type="number" id="price" name="price" min="0" step="0.01"><br><br>

                <input type="submit" name="btnBid" value="Place Bid">
                <?php if ($auction->buyNowPrice > -1):?>
                    <input type="submit" name="btnBIN" value="Buy it now">
                <?php endif ?>
            </form>
        </div>
    <?php else: ?>
        <h1>Auction is over</h1>
    <?php endif ?>
    <?php endif ?>
</body></html>

