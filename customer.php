<?php  


if (isset($_POST['customer_id']) && isset($_POST['cname']) && isset($_POST['address'])) {
	include 'customer_con.php';

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$customer_id = validate($_POST['customer_id']);
	$cname = validate($_POST['cname']);
	$address = validate($_POST['address']);


	if (empty($customer_id) || empty($cname) || empty($address)) {
		header("Location: customer1.php");}
	//else {

// 		$sql = "INSERT INTO customer(customer_id,cname, address) VALUES('$customer_id', '$cname', '$address')";
// 		$res = mysqli_query($conn, $sql);
// 	if ($res) {
// 		echo "<script>";
// 		echo "alert('Customer $customer_id has been successfully stored in the database.');";
// 		echo "window.location.href='order1_front.php';";
// 		echo "</script>";
// 	} else {
// 		echo "Your message could not be sent!";
// 	}

// 	}
	
// }else {
// 	header("Location: customer1.php");
// }


else {

        $check_customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
        $check_customer_result = mysqli_query($conn, $check_customer_query);
        // If the query returns a result, it means that the art_title exists
        if (mysqli_num_rows($check_customer_result) > 0) {
            // Display an alert to the user
            echo "<script>";
            echo "alert('The entered customer already exists. Use another Customer ID');";
            echo "window.location.href='customer.php';";
            echo "</script>";
        } else {
            // Insert the data into the orderarts table
            $sql = "INSERT INTO customer(customer_id,cname, address) VALUES('$customer_id', '$cname', '$address')";
            $res = mysqli_query($conn, $sql);
  			}
  			if ($res) {
		echo "<script>";
		echo "alert('Customer $customer_id has been successfully stored in the database.');";
		echo "window.location.href='order1_front.php';";
		echo "</script>";
	} else {
		echo "Your message could not be sent!";
	}
    }
}else {
	header("Location: customer1.php");
}