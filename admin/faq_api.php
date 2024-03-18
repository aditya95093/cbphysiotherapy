<?php
require ('includes/connection.inc.php');
require ('function.inc.php');
$response = array();

if ($con) {
    $sql = "SELECT * FROM faq";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header("Content-Type: JSON");
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $response[$i]['id'] = $row['id'];
            $response[$i]['question'] = $row['question'];
            $response[$i]['answer'] = $row['answer'];
            $response[$i]['category_id'] = $row['question_category_id'];
            $i++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);

    } else {
        echo "Database Connection failed";
    }
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>