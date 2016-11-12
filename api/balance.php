<?php
        require 'config.php';
        $method = $_GET["action"];
        $master_id = "master@master.com";
        // Open database
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        if (!$db)
                die("Connection failed: " . mysqli_connect_error());
        // Always get current balance amount
        $query = "SELECT balance FROM profiles WHERE email='{$master_id}'";
        $result = mysqli_query($db, $query);
        //Should only be one row
        $row = mysqli_fetch_assoc($result);
        $current_balance = $row["balance"];
        if (strcmp("get", $method) == 0) {
                echo $current_balance;
        } else {
                // Get amount specified in URL and add/subtract from balance
                $amount = intval($_GET["amount"]);
                $current_balance += strcmp("add", $method) ? -$amount : $amount;
                $newquery = "UPDATE profiles SET balance='$current_balance' WHERE email='{$master_id}'";
                if (mysqli_query($db, $newquery)){
                        echo "Success";
               } else {
                        echo "Fail";
               }
        }
        mysqli_close($db);
 ?>
