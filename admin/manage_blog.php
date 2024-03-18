<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

if (isset ($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteQuery = "DELETE FROM blog_posts WHERE id = $deleteId";
    $deleteResult = mysqli_query($con, $deleteQuery);
    if ($deleteResult) {
        header('location: manage_blog.php');
        exit();
    } else {
        die ("Error deleting blog: " . mysqli_error($con));
    }
}

$blogQuery = "SELECT * FROM blog_posts";
$result = mysqli_query($con, $blogQuery);

if ($result) {
    $blog_posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die ("Error fetching blog_posts: " . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #333333;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #4caf50;
            color: white;
        }

        img {
            width: 100%;
            height: auto;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .view-button,
        .edit-button,
        .delete-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
        }

        .view-button {
            background-color: green;
        }

        .view-button:hover {
            background-color: darkgreen;
        }

        .edit-button:hover {
            background-color: blueviolet;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: red;
        }

        @media only screen and (max-width: 600px) {

            th,
            td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }

            th {
                display: none;
            }

            td {
                margin-bottom: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Blog_Post</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($blog_posts as $blog): ?>
                <tr>
                    <td>
                        <?php echo $blog['title'] ?>
                    </td>
                    <td><img src="<?php echo $blog['image']; ?>" alt="Blog Image" width="80"></td>
                    <td>
                        <?php

                        $content = $blog['content'];
                        if (strlen($content) > 100) {
                            $content = substr($content, 0, 100) . '...';
                        }
                        echo $content;
                        ?>
                    </td>
                    <td class="action-buttons">
                        <a href="view_blog.php?id=<?php echo $blog['id']; ?>" class="view-button">View</a>
                        <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" class="edit-button">Edit</a>
                        <a href="manage_blog.php?delete=<?php echo $blog['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this blog?')"
                            class="delete-button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php" style="text-decoration: none; color: #007bff;">Back to Dashboard</a>
    </div>
</body>

</html>