<?php
	require 'config.php';
	// both requests have action and email
	$action = $_GET["action"];
	$email = $_GET["email"];
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	if (strcmp($action, "add") == 0) {
		$password = $_GET["password"];
		$name = $_GET["name"];
		$address = $_GET["address"];
		$balance = intval($_GET["balance"]);
		$budget = intval($_GET["budget"]);
		$radius	 = intval($_GET["radius"]);
		$sql = "INSERT INTO profiles (email, password, name, address, balance, budget, radius) VALUES ('$email', '$password', '$name', '$address', '$balance', '$budget', '$radius')";
    if (mysqli_query($conn, $sql)) {
			echo "Success";
		} else {
			echo "Fail";
		}
	} else if (strcmp($action, "addbalance") == 0) {
		$email = $_GET["email"];
		$amount = $_GET["amount"];
		
		$query = "SELECT balance FROM profiles WHERE email = '$email'";
		$result = mysqli_query($conn, $query);
		$array = mysqli_fetch_assoc($result);
		$amount += $array["balance"];
		
		$sql = "UPDATE profiles SET balance='$amount' WHERE email = '$email'";
		    if (mysqli_query($conn, $sql)) {
			echo "Success";
		} else {
			echo "Fail";
		}
	} else if (strcmp($action, "subtractbalance") == 0) {
		$email = $_GET["email"];
		$amount = $_GET["amount"];
		
		$query = "SELECT balance FROM profiles WHERE email = '$email'";
		$result = mysqli_query($conn, $query);
		$array = mysqli_fetch_assoc($result);
		$amount = $array["balance"] - $amount;
		
		$sql = "UPDATE profiles SET balance='$amount' WHERE email = '$email'";
		    if (mysqli_query($conn, $sql)) {
			echo "Success";
		} else {
			echo "Fail";
		}
	} else if (strcmp($action, "get") == 0) {
		$query = "SELECT * FROM profiles WHERE email = '$email'";
		$result = mysqli_query($conn, $query);
		$array = mysqli_fetch_assoc($result);
		$json = json_encode($array);
		echo $json;
	} else if (strcmp($action, "verify") == 0) {
	  $success = false;
	  $pass = $_GET["password"];
    $sql = "SELECT * FROM profiles WHERE email='$email' AND password='$pass'";
		$success = false;
		$result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)) {
        $success = true;
    }
    if($success == true) {
        echo 'Success';
    } else {
        echo 'Fail';
    }
	}
	mysqli_close($conn);
?>
