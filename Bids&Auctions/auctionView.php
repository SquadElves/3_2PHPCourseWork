<?php
    require "app/include/header.php";
    require "app/classes/Auction.php";
?>

<?php
    if(isset($_GET['id'])) {
        try {
            $auction = new Auction($_GET['id']);
        }catch (Exception $exception){
            echo $exception->getMessage();
        }
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
    <h1>Online Auction - Item Details</h1>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Description</th>
            <th>Starting Price</th>
            <th>Current Highest Bid</th>
            <th>Buy It Now Price</th>
        </tr>
        <tr>
            <td>Example Item 1</td>
            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
            <td>$100</td>
            <td>$150</td>
            <td>$500</td>
        </tr>
    </table>

    <div class="bid-form">
        <h2>Place Your Bid</h2>
        <form action="" method="post">
            <label for="price">Bid Amount:</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required><br><br>

            <input type="submit" value="Place Bid">
        </form>
    </div>
</body></html>

