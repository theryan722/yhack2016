<?php
	require 'config.php';
	// both requests have action and email
	$action = $_POST["action"];
	$email = $_POST["email"];
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	if (strcmp($action, "add") == 0) {
		$password = $_POST["password"];
		$name = $_POST["name"];
		$address = $_POST["address"];
		$balance = intval($_POST["balance"]);
		$budget = intval($_POST["budget"]);
		$radius	 = intval($_POST["radius"]);
		$sql = "INSERT INTO profiles (email, password, name, address, balance, budget, radius) VALUES ('$email', '$password', '$name', '$address', '$balance', '$budget', '$radius')";
    if (mysqli_query($conn, $sql)) {
			echo "Success";
		} else {
			echo "Fail";
		}
	} else if (strcmp($action, "get") == 0) {
		$query = "SELECT * FROM $profiles WHERE email = $email";
		$result = mysqli_query($conn, $query);
		$array = mysqli_fetch_assoc($result);
		$json = json_encode($array);
		echo $json;
	}
	mysqli_close($conn);
?>
