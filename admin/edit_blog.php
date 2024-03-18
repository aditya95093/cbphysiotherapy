<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset ($_POST['id'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = mysqli_real_escape_string($con, $_POST['editor_content']);


        if ($_FILES['image']['name']) {
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);


            if (!is_dir("uploads/")) {
                mkdir("uploads/");
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {

                $updateQuery = "UPDATE blog_posts SET title='$title', image='$target', content='$content' WHERE id=$id";
            } else {
                die ("Error uploading image.");
            }
        } else {

            $updateQuery = "UPDATE blog_posts SET title='$title', content='$content' WHERE id=$id";
        }

        $updateResult = mysqli_query($con, $updateQuery);

        if (!$updateResult) {
            die ("Error updating blog_posts: " . mysqli_error($con));
        }

        echo "<script>";
        echo "alert('Blog_Post Updated Successfully');";
        echo "window.location.href = 'manage_blog.php';";
        echo "</script>";
        exit();
    } else {
        die ("Invalid request.");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        .wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        textarea {
            height: 200px;
            resize: vertical;
        }

        .btn-primary {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
    <script src="tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: "#mytextarea",
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent ident | ' +
                'removeformat | help',
            content_style: 'body {font-family:Helvetica,Arial,sans-serif; font-size:16px}'
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <h2>Edit Blog</h2>
            <?php
            if (isset ($_GET['id'])) {
                $id = $_GET['id'];
                $selectQuery = "SELECT * FROM blog_posts WHERE id = $id";
                $result = mysqli_query($con, $selectQuery);
                if ($result) {
                    $blog = mysqli_fetch_assoc($result);
                    ?>
                    <form method="POST" action="edit_blog.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <label>Title:</label><br>
                        <input type="text" name="title" value="<?php echo $blog['title']; ?>"><br>
                        <label>Image:</label><br>
                        <img src="<?php echo $blog['image']; ?>" alt="Current Image" style="max-width: 200px;"><br>
                        <input type="file" name="image"><br>
                        <label>Content:</label><br>
                        <textarea id="mytextarea" name="editor_content"><?php echo $blog['content']; ?></textarea><br>
                        <input type="submit" value="Update" class="btn-primary">
                    </form>
                    <?php
                } else {
                    echo "Error fetching blog post: " . mysqli_error($con);
                }
            }
            ?>
        </div>
    </div>
</body>

</html>