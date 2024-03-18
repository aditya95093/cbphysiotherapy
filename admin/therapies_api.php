<?php
$con = mysqli_connect("localhost", "root", "", "cbphysiotherapy");
$response = array();

if ($con) {
    $sql = "SELECT therapies.id, 
                   therapies.title, 
                   therapies.description, 
                   therapies.image_url, 
                   therapies.category_id
            FROM therapies";

    $result = mysqli_query($con, $sql);

    if ($result) {
        header("Content-Type: application/json");

        
        $response = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $therapy = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'image_url' => $row['image_url'],
                'category_id' => $row['category_id'],
                'qa_data' => array()
            );

            
            $qa_sql = "SELECT JSON_OBJECT('question', question, 'answer', answer) AS qa_pair
                       FROM therapy_qa
                       WHERE therapy_id = " . $row['id'];

            $qa_result = mysqli_query($con, $qa_sql);

            if ($qa_result) {
                while ($qa_row = mysqli_fetch_assoc($qa_result)) {
                    $therapy['qa_data'][] = json_decode($qa_row['qa_pair'], true);
                }
            }

            
            $response[] = $therapy;
        }

        
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo "Database Connection failed";
    }
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>