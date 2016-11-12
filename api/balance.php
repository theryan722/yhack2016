<?php
        $method = $_GET["action"];

        // Open database
        

        if (!$db)
                die("Connection failed: " . mysqli_connect_error());

        // Always get current balance amount
        $query = "SELECT balance FROM profiles WHERE email='email@email.com'";
        $result = mysqli_query($db, $query);

        //Should only be one row
        $row = mysqli_fetch_assoc($result);
        $current_balance = $row["balance"];

        if (strcmp("get", $method) == 0) {
                echo $current_balance;

        } else {
                // Get amount specified in URL and add/subtract from balance
                $amount = intval($_GET["amount"]);

                $current_balance += strcmp("add"), $method) ? -$amount : $amount;

                $newquery = "UPDATE profiles SET balance=$current_balance WHERE email='email@email.com'";
                if (mysqli_query($db, $newquery))
                        echo "Record updated successfully";
                else
                        echo "Error updating record: " . mysqli_error($db);
        }

        mysqli_close($db);

 ?>
