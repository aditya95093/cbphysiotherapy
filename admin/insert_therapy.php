<?php
require ('includes/connection.inc.php');

$categoryQuery = "SELECT id, name FROM categories";
$result = mysqli_query($con, $categoryQuery);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die ("Error fetching categories: " . mysqli_error($con));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    $imageFileName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageUploadPath = 'img/' . $imageFileName;

    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
        $therapySql = "INSERT INTO therapies (title, description, image_url, category_id) 
            VALUES (?, ?, ?, ?)";

        $stmt = mysqli_stmt_init($con);
        $prepareStmt = mysqli_stmt_prepare($stmt, $therapySql);

        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $imageUploadPath, $category_id);
            mysqli_stmt_execute($stmt);

            $therapy_id = mysqli_insert_id($con);

            $questions = $_POST['questions'];
            $answers = $_POST['answers'];


            foreach ($questions as $index => $question) {
                $answer = $answers[$index];

                $qaSql = "INSERT INTO therapy_qa (therapy_id, question, answer) VALUES (?, ?, ?)";
                $qaStmt = mysqli_stmt_init($con);
                $prepareQAStmt = mysqli_stmt_prepare($qaStmt, $qaSql);

                if ($prepareQAStmt) {
                    mysqli_stmt_bind_param($qaStmt, "iss", $therapy_id, $question, $answer);
                    mysqli_stmt_execute($qaStmt);
                } else {
                    die ("Error preparing question and answer statement: " . mysqli_error($con));
                }
            }

            echo "<script>";
            echo "alert('Therapy added successfully');";
            echo "window.location.href = 'dashboard.php';";
            echo "</script>";
            die();
        } else {
            die ("Something went wrong");
        }
    } else {
        echo "File upload failed!";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="assets/js/plugins.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Add Therapy Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #dddddd;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #ffffff;
            padding: 10px;
            margin-top: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        $(document).ready(function () {
            var questionCount = 1;

            $('#addQuestion').click(function () {
                questionCount++;
                var html = '<div class="question-block">' +
                    '<label for="question_' + questionCount + '">Question ' + questionCount + ':</label>' +
                    '<input type="text" name="questions[]" placeholder="Enter question" required>' +
                    '<label for="answer_' + questionCount + '">Answer ' + questionCount + ':</label>' +
                    '<textarea name="answers[]" rows="4" placeholder="Enter answer" required></textarea>' +
                    '</div>';
                $('#questions-container').append(html);
            });
        });
    </script>

</head>

<body>
    <div class="container">
        <h2>Add New Therapy</h2>

        <form action="insert_therapy.php" method="post" enctype="multipart/form-data" id="therapyForm">
            <label for="title">Title:</label>
            <input type="text" name="title" placeholder="Title" required>

            <label for="description">Description:</label>
            <textarea name="description" rows="4" placeholder="Write description" required></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <div id="questions-container">
                <div class="question-block">
                    <label for="questions[]">Question 1:</label>
                    <input type="text" name="questions[]" placeholder="Enter question" required>

                    <label for="answers[]">Answer 1:</label>
                    <textarea name="answers[]" rows="4" placeholder="Enter answer" required></textarea>
                </div>
            </div>



            <label for="category_id">Category:</label>
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo $category['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button id="addQuestion">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button type="submit" name="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Add Therapy</button>


        </form>
    </div>
</body>


</html>