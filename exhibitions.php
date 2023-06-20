<?php  


if (isset($_POST['e_id']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['location'])) {
	include 'customer_con.php';

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$e_id = validate($_POST['e_id']);
	$start_date = validate($_POST['start_date']);
	$end_date = validate($_POST['end_date']);
	$location = validate($_POST['location']);


	if (empty($e_id) || empty($start_date) || empty($end_date) || empty($location)) {
		header("Location: exhibitions_front.php");
	}else {

		$sql = "INSERT INTO exhibition(e_id,start_date, end_date, location) VALUES('$e_id', '$start_date', '$end_date', '$location')";
		$res = mysqli_query($conn, $sql);
	if ($res) {
		echo "<script>";
		echo "alert('exhibition $e_id has been successfully stored in the database.');";
		echo "window.location.href='exhibition1_front.php';";
		echo "</script>";
	} else {
		echo "Your message could not be sent!";
	}

	}

}else {
	header("Location: exhibitions_front.php");
}