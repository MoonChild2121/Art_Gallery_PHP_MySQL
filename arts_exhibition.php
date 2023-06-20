<?php  


if (isset($_POST['e_id']) && isset($_POST['art_title'])) {
    include 'customer_con.php';

    function validate($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }
    $e_id = validate($_POST['e_id']);
    $art_title = validate($_POST['art_title']);
    
    if (empty($e_id) || empty($art_title)) {
        header("Location: arts_exhibition.php");

    }else {

        $check_art_title_query = "SELECT * FROM orderarts WHERE art_title = '$art_title'";
        $check_art_title_result = mysqli_query($conn, $check_art_title_query);
        // If the query returns a result, it means that the art_title exists
        if (mysqli_num_rows($check_art_title_result) > 0) {
            // Display an alert to the user
            echo "<script>";
            echo "alert('The entered art title has been sold. Please enter a different art title.');";
            echo "window.location.href='arts_exhibition.php';";
            echo "</script>";
        } else {
            // Insert the data into the orderarts table
            $sql = "INSERT INTO arts_in_exhibition(e_id, art_title) VALUES('$e_id', '$art_title')";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                echo "<script>";
                echo "alert('art $art_title has been added');";
                echo "window.location.href='arts_exhibition.php';";
                echo "</script>";
            } else {
                echo "Your message could not be sent!";
          }  }
    }

}else {
    header("Location: exhibition1_front.php");
}