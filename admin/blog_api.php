<?php
$con = mysqli_connect("localhost", "root", "", "cbphysiotherapy");
$response = array();

if ($con) {
    $sql = "SELECT * FROM blog_posts";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header("Content-Type: JSON");
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $response[$i]['id'] = $row['id'];
            $response[$i]['title'] = $row['title'];
            $response[$i]['image'] = $row['image'];
            $content = $row['content'];
            $contentLines = explode("\n", $content);
            $truncatedContent = implode("\n", array_slice($contentLines, 0, 3));
            $response[$i]['content'] = $truncatedContent;

            $i++;

        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo "Database Connection Failed";
    }
} else {
    echo "Connection Failed:" . mysqli_connect_errno();
}

?>