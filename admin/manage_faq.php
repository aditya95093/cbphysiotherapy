<?php
require ('includes/connection.inc.php');
require ('function.inc.php');

if (isset ($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteQuery = "DELETE FROM faq WHERE id = $deleteId";
    $deleteResult = mysqli_query($con, $deleteQuery);
    if ($deleteResult) {
        header('location: manage_faq.php');
        exit();
    } else {
        die ("Error deleting faq: " . mysqli_error($con));
    }
}


$faqQuery = "SELECT faq.*, question_categories.question_category_name 
             FROM faq 
             INNER JOIN question_categories 
             ON faq.question_category_id = question_categories.id";

$result = mysqli_query($con, $faqQuery);
if ($result) {
    $faq = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die ("Error fetching faq:" . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQ</title>
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

        .container {
            width: 100%;
            margin-top: -15rem;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;

        }

        h2 {
            text-align: center;
            color: #333333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: white;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .edit-button,
        .delete-button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px;
            margin: 2px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
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


        thead,
        tbody {
            display: block;
        }

        tbody {
            max-height: 300px;

            overflow-y: auto;

        }

        thead {

            position: sticky;
            top: 0;
            background-color: #ffffff;
        }

        tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            width: 20%;

        }

        body {
            position: relative;
        }

        .back-to-dashboard {
            position: absolute;
            bottom: 90px;
            left: 10px;
            text-decoration: none;
            color: #007bff;
        }

        @media screen and (max-width: 768px) {
            .back-to-dashboard {
                position: static;
                margin-top: 10px;
                display: block;
                text-align: center;
            }
        }


        @media screen and (max-width: 768px) {
            table {
                width: 100%;
            }

            th,
            td {
                display: block;
                width: auto;
            }

            th {
                height: 40px;

            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Faq</h2>
        <table>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
            <?php foreach ($faq as $item): ?>
                <tr>
                    <td>
                        <?= $item['question']; ?>
                    </td>
                    <td>
                        <?= $item['answer']; ?>
                    </td>
                    <td>
                        <?= $item['question_category_name']; ?>
                    </td>
                    <td>
                    <td>
                        <a href="edit_faq.php?id=<?= $item['id']; ?>" class="edit-button">Edit</a>
                        <a href="manage_faq.php?delete=<?= $item['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this faq?')"
                            class="delete-button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
    <a href="dashboard.php" class="back-to-dashboard">Back to Dashboard</a>
</body>

</html>