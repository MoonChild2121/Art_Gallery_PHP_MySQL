<?php  


if (isset($_POST['order_id']) && isset($_POST['customer_id'])) {
	include 'customer_con.php';

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	$order_id = validate($_POST['order_id']);
	$customer_id = validate($_POST['customer_id']);

	if (empty($order_id) || empty($customer_id)) {
		header("Location: order1_front.php");
	}else {

		$sql = "INSERT INTO orders(order_id, customer_id) VALUES('$order_id', '$customer_id')";
		$res = mysqli_query($conn, $sql);
		$sql = "INSERT INTO orders_of_customer(customer_id, order_id) VALUES('$customer_id', '$order_id')";
		$res = mysqli_query($conn, $sql);

	if ($res) {
		echo "<script>";
		echo "alert('Order $order_id has been successfully stored in the databse.');";
		echo "window.location.href='order2_front.php';";
		echo "</script>";
	} else {
		echo "Your message could not be sent!";
	}

	}

}else {
	header("Location: order2_front.php");
}