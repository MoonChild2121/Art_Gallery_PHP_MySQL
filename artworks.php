<?php  


if (isset($_POST['art_title']) && isset($_POST['releaseyear']) && isset($_POST['typeofart']) && isset($_POST['price']) && isset($_POST['artist_id'])) {
	include 'customer_con.php';

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$art_title = validate($_POST['art_title']);
	$releaseyear = validate($_POST['releaseyear']);
	$typeofart = validate($_POST['typeofart']);
	$price = validate($_POST['price']);
	$artist_id = validate($_POST['artist_id']);


	if (empty($art_title) || empty($releaseyear) || empty($typeofart) || empty($price) || empty($artist_id)) {
		header("Location: artworks_front.php");
	}else {

		$sql = "INSERT INTO artwork(art_title,releaseyear, typeofart, price, artist_id) VALUES('$art_title', '$releaseyear', '$typeofart','$price' ,'$artist_id')";
		$res = mysqli_query($conn, $sql);
	if ($res) {
		echo "<script>";
		echo "alert('artwork $art_title has been successfully stored in the database.');";
		echo "window.location.href='artworks_front.php';";
		echo "</script>";
	} else {
		echo "Your message could not be sent!";
	}

	}

}else {
	header("Location: artworks_front.php");
}