<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

$categoryQuery = "SELECT id, question_category_name FROM question_categories";
$result = mysqli_query($con, $categoryQuery);
if (!$result) {
    die ("Error fetching categories: " . mysqli_error($con));
}
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset ($_POST['id'])) {
        $id = $_POST['id'];
        $questions = $_POST['questions'];
        $answers = $_POST['answers'];
        $category_id = $_POST['category_id'];


        $deleteQuery = "DELETE FROM faq WHERE id = $id";
        $deleteResult = mysqli_query($con, $deleteQuery);
        if (!$deleteResult) {
            die ("Error deleting existing FAQ entries: " . mysqli_error($con));
        }


        foreach ($questions as $index => $question) {
            $answer = $answers[$index];

            $question = mysqli_real_escape_string($con, $question);
            $answer = mysqli_real_escape_string($con, $answer);
            $category_id = mysqli_real_escape_string($con, $category_id);

            $insertQuery = "INSERT INTO faq (question, answer, question_category_id) VALUES ('$question', '$answer', '$category_id')";
            $insertResult = mysqli_query($con, $insertQuery);

            if (!$insertResult) {
                die ("Error inserting FAQ: " . mysqli_error($con));
            }
        }

        echo "<script>";
        echo "alert('FAQ updated successfully');";
        echo "window.location.href = 'manage_faq.php';";
        echo "</script>";
    } else {
        die ("Invalid request.");
    }
}

if (isset ($_GET['id'])) {
    $id = $_GET['id'];
    $faqQuery = "SELECT * FROM faq WHERE id = $id";
    $result = mysqli_query($con, $faqQuery);
    if ($result) {
        $faq = mysqli_fetch_assoc($result);
        if (!$faq) {
            die ("FAQ not found.");
        }
    } else {
        die ("Error fetching FAQ: " . mysqli_error($con));
    }
} else {
    die ("FAQ ID not provided.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Edit FAQ</title>
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
</head>

<body>
    <div class="container">
        <h2>Edit FAQ</h2>
        <form action="edit_faq.php" method="post">
            <input type="hidden" name="id" value="<?= $faq['id']; ?>">
            <div id="questions-container">
                <div class="question-block">
                    <label for="question_1">Question 1:</label>
                    <input type="text" name="questions[]" value="<?= $faq['question']; ?>" placeholder="Enter question"
                        required>
                    <label for="answer_1">Answer 1:</label>
                    <textarea name="answers[]" rows="4" placeholder="Enter answer"
                        required><?= $faq['answer']; ?></textarea>
                </div>
            </div>
            <label for="category_id">Category:</label>
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id']; ?>" <?= ($category['id'] == $faq['question_category_id']) ? 'selected' : ''; ?>>
                        <?= $category['question_category_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button id="addQuestion">
                <i class="fas fa-plus-circle"></i>
            </button>
            <button type="submit">Update FAQ</button>
        </form>
    </div>

    <script>
        document.getElementById('addQuestion').addEventListener('click', function () {
            var questionCount = document.querySelectorAll('.question-block').length + 1;
            var html = '<div class="question-block">' +
                '<label for="question_' + questionCount + '">Question ' + questionCount + ':</label>' +
                '<input type="text" name="questions[]" placeholder="Enter question" required>' +
                '<label for="answer_' + questionCount + '">Answer ' + questionCount + ':</label>' +
                '<textarea name="answers[]" rows="4" placeholder="Enter answer" required></textarea>' +
                '</div>';
            document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
        });
    </script>
</body>

</html>