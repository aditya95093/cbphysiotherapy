<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

if (isset ($_GET['id'])) {
    $id = $_GET['id'];
    $selectQuery = "SELECT * FROM blog_posts WHERE id = $id";
    $result = mysqli_query($con, $selectQuery);
    if ($result && mysqli_num_rows($result) > 0) {
        $blog = mysqli_fetch_assoc($result);
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>View Blog</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }

                .container {
                    width: 80%;
                    margin: auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                h2 {
                    color: #333333;
                }

                p {
                    line-height: 1.6;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <h2>
                    <?php echo $blog['title']; ?>
                </h2>
                <img src="<?php echo $blog['image']; ?>" alt="Blog Image" style="max-width: 100%;">
                <p>
                    <?php echo $blog['content']; ?>
                </p>
                <a href="manage_blog.php" style="text-decoration: none; color: #007bff;">Back to Manage Blog</a>
            </div>
        </body>

        </html>
        <?php
    } else {
        echo "Blog not found.";
    }
} else {
    echo "Invalid request.";
}
?>