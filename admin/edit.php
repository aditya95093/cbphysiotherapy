<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

$therapyId = isset ($_GET['id']) ? $_GET['id'] : '';

if (!empty ($therapyId)) {
    $therapyQuery = "SELECT * FROM therapies WHERE id = $therapyId";
    $therapyResult = mysqli_query($con, $therapyQuery);

    if ($therapyResult) {
        $therapyData = mysqli_fetch_assoc($therapyResult);

        if (!$therapyData) {
            die ("Therapy not found for ID: $therapyId");
        }
    } else {
        die ("Error fetching therapy details: " . mysqli_error($con));
    }

    $categoriesQuery = "SELECT id, name FROM categories";
    $categoriesResult = mysqli_query($con, $categoriesQuery);

    if ($categoriesResult) {
        $categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);
    } else {
        die ("Error fetching categories: " . mysqli_error($con));
    }

    if (isset ($_POST['update'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];


        $imageUploadPath = '';
        if (isset ($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFileName = $_FILES['image']['name'];
            $imageTmpName = $_FILES['image']['tmp_name'];
            $imageUploadPath = 'img/' . $imageFileName;

            if (move_uploaded_file($imageTmpName, $imageUploadPath)) {

            } else {
                die ("Error uploading image");
            }
        }

        $updateQuery = "UPDATE therapies SET title='$title', description='$description',  category_id='$category_id'";
        if (!empty ($imageUploadPath)) {
            $updateQuery .= ", image_url='$imageUploadPath'";
        }
        $updateQuery .= " WHERE id=$therapyId";

        $updateResult = mysqli_query($con, $updateQuery);

        if (!$updateResult) {
            die ("Error updating therapy: " . mysqli_error($con));
        }


        if (isset ($_POST['questions']) && isset ($_POST['answers'])) {
            $questions = $_POST['questions'];
            $answers = $_POST['answers'];


            $deleteQuery = "DELETE FROM therapy_qa WHERE therapy_id = $therapyId";
            $deleteResult = mysqli_query($con, $deleteQuery);

            if (!$deleteResult) {
                die ("Error deleting existing questions and answers: " . mysqli_error($con));
            }




            $insertQuery = "INSERT INTO therapy_qa (therapy_id, question, answer) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $insertQuery);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'iss', $therapyId, $question, $answer);

                foreach ($questions as $index => $question) {
                    $answer = $answers[$index];

                    mysqli_stmt_execute($stmt);

                    if (mysqli_stmt_affected_rows($stmt) <= 0) {
                        die ("Error inserting question and answer: " . mysqli_error($con));
                    }
                }
            } else {
                die ("Error preparing statement: " . mysqli_error($con));
            }


        }

        echo "<script>alert('Therapy updated successfully'); window.location.href = 'manage_therapies.php';</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Edit Therapy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            color: #333333;
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
        <h2>Edit Therapy</h2>

        <form action="edit.php?id=<?php echo $therapyId; ?>" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title"
                value="<?php echo isset ($therapyData['title']) ? $therapyData['title'] : ''; ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" rows="4"
                required><?php echo isset ($therapyData['description']) ? $therapyData['description'] : ''; ?></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" accept="image/*">

            <div id="questions-container">
                <?php
                $qaQuery = "SELECT * FROM therapy_qa WHERE therapy_id = $therapyId";
                $qaResult = mysqli_query($con, $qaQuery);
                if ($qaResult && mysqli_num_rows($qaResult) > 0) {
                    while ($qaData = mysqli_fetch_assoc($qaResult)) {
                        ?>
                        <div class="question-block">
                            <label for="question[]">Question:</label>
                            <input type="text" name="questions[]" value="<?php echo $qaData['question']; ?>"
                                placeholder="Enter question" required>

                            <label for="answer[]">Answer:</label>
                            <textarea name="answers[]" rows="4" placeholder="Enter answer"
                                required><?php echo $qaData['answer']; ?></textarea>
                        </div>
                        <?php
                    }
                }
                ?>

                <label for="category_id">Category:</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo (isset ($therapyData['category_id']) && $category['id'] == $therapyData['category_id']) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button id="addQuestion">
                    <i class="fas fa-plus-circle"></i>
                </button>
                <button type="submit" name="update">Update Therapy</button>
        </form>
    </div>
</body>

</html>