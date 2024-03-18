<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

if (isset ($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['editor_content'];

    $imageFileName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageUploadPath = 'img/' . $imageFileName;


    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
        $stmt = $con->prepare("INSERT INTO blog_posts (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $imageUploadPath);

        if ($stmt->execute()) {
            echo "<script>alert('Blog post added successfully!'); window.location='manage_blog.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "File upload failed.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add Blog</title>
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
            <form action="Blog.php" method="post" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required><br><br>
                <textarea id="mytextarea" name="editor_content"></textarea><br><br>
                <input type="file" name="image" accept="image/*" required><br><br>
                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
            </form>

        </div>
    </div>
</body>

</html>