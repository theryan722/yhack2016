<?php
        require 'config.php';
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
                VALUES ('{$type}', '{$dsc}', '{$address}', '{$price}', '{$email}', '{$state}', '{$id}')";
                if (mysqli_query($db, $query))
                        echo "New record created successfully";
                else
                        echo "Error: " . $db . mysqli_error($db);
        } else if (strcmp("update", $method) == 0) {
                $id = intval($_GET["id"]);
                $state = intval($_GET["state"]);
                $query = "UPDATE requests SET state='{$state}' WHERE id='{$id}'";
                if (mysqli_query($db, $query))
                        echo "Record updated successfully";
                else
                        echo "Error updating row";
        } else if (strcmp("get", $method) == 0) {
                $id = intval($_GET["id"]);
                $query = "SELECT * FROM requests WHERE id='{$id}'";
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
                } else {
                        echo "Failed retrieving all rows\n";
                }
                $nearest_requests = array();
                foreach ($array as $number => $row) {
                        $address2 = $row["address"];
                        $result = getDistance($address, $address2);
                        if ($result <= $radius)
                                $nearest_requests[] = $row;
                }
                $json = json_encode($nearest_requests);
                echo $json;
        }
        function getDistance($addressFrom, $addressTo){
          $formattedAddrFrom = str_replace(' ','+',$addressFrom);
          $formattedAddrTo = str_replace(' ','+',$addressTo);
          $geocodeFrom = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false');
          $outputFrom = json_decode($geocodeFrom);
          $geocodeTo = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false');
          $outputTo = json_decode($geocodeTo);
          $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
          $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
          $latitudeTo = $outputTo->results[0]->geometry->location->lat;
          $longitudeTo = $outputTo->results[0]->geometry->location->lng;
          $theta = $longitudeFrom - $longitudeTo;
          $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          return round($miles);
}
?>
