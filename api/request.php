require ("config.php");

<?php
        // Open database
        $method = $_GET["action"];

        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        if (!$db)
                die("Connection failed: " . mysqli_connect_error());

        if (strcmp("add", $method) == 0) {
                $type = $_GET["type"];
                $dsc = $_GET["description"];
                $address = $_GET["address"];
                $price = intval($_GET["price"]);
                $email = $_GET["email"];
                $state = intval($_GET["state"]);
                $id = rand();

                $query = "INSERT INTO requests (type, description, address, price, email, state, id)
                VALUES ($type, $dsc, $address, $price, $email, $state, $id)";

                if (mysqli_query($db, $query))
                        echo "New record created successfully";
                else
                        echo "Error: " . $db . mysqli_error($db);

        } else if (strcmp("update", $method) == 0) {
                $id = intval($_GET["id"]);
                $state = intval($_GET["state"]);

                $query = "UPDATE requests SET state=$state WHERE id=$id";

                if (mysqli_query($db, $query))
                        echo "Record updated successfully";
                else
                        echo "Error updating row";

        } else if (strcmp("get", $method) == 0) {
                $id = intval($_GET["id"]);

                $query = "SELECT * FROM profiles WHERE id=$id";
                $result = mysqli_query($db, $query);

                $row = mysqli_fetch_assoc($result);

                $json = json_encode($row);

                echo $json;

        } else if (strcmp("getlist", $method) == 0) {
                $address = $_GET["address"];
                $radius = intval($_GET["radius"]);

                $query = "SELECT * FROM requests";
                $result = mysqli_query($db, $query);
                $array = array();

                if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                                $array[] = $row;
                        }
                        ///Check to see if it worked
                        var_dump($array);

                } else {
                        echo "Failed retrieving all rows\n";
                }

                $nearest_requests = array();

                foreach ($array as $number => $row) {
                        $address2 = $row["address"];
                        ob_start();
                        include "getdistance.php?address1=" . $address . "&address2=" . $address2;
                        $result = intval(ob_get_clean());

                        if ($result <= $radius)
                                $nearest_requests[] = $row;
                }

                echo "Test DUMP\n";
                var_dump($nearest_requests);

                $json = json_encode($nearest_requests);
                echo $json;
        }

?>
