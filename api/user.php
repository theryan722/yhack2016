require ("config.php");

<?php
	// both requests have action and email
	$action = $_GET("action");
	$email = $_GET("email");

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATEBASE);

	if (strcmp($action, "add") == 0) {
		
		$password = $_GET("password")
		$name = $_GET("name");
		$address = $_GET("address");
		$balance = $_GET("balance");
		$budget = $_GET("budget");
		$radius	 = $_GET("radius");


		$sql = "INSERT INTO profiles (email, password, name, address, balance, budget, radius)
				VALUES ($email, $password, $name, $address, $balance, $budget, $radius)";

		if (mysqli_query($conn, $sql)) {
			echo "Successful entry";
		}
		else {
			echo "Entry failure";
		}

	}
	else if (strcmp($action, "get") == 0) {
		$query = "SELECT * FROM $profiles WHERE email = $email LIMIT 1";
		$result = mysqli_query($conn, $query);
		$array = mysqli_fetch_assoc($result);
		$json - json_encode($array);
		echo $json;
	}

	mysqli_close($conn);
?>
