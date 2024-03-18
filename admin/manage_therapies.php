<?php
require ('includes/connection.inc.php');
require ('function.inc.php');
header('location:dashboard.php');



if (isset ($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $deleteQuery = "DELETE FROM therapies WHERE id = $deleteId";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult) {
        header("Location: manage_therapies.php");
        exit();
    } else {
        die ("Error deleting therapy: " . mysqli_error($con));
    }
}


$therapiesQuery = "SELECT * FROM therapies";
$result = mysqli_query($con, $therapiesQuery);

if ($result) {
    $therapies = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die ("Error fetching therapies: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Therapies</title>
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
            margin-top: -20rem;
            text-align: center;
            color: #333333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .action-buttons {
            display: flex;
            gap: 10px;
        }

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
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Manage Therapies</h2>

        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($therapies as $therapy): ?>
                <tr>
                    <td>
                        <?php echo $therapy['title']; ?>
                    </td>
                    <td>
                        <?php echo $therapy['description']; ?>
                    </td>
                    <td><img src="<?php echo $therapy['image_url']; ?>" alt="Therapy Image" width="50"></td>
                    <td>
                        <?php echo getCategoryName($con, $therapy['category_id']); ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $therapy['id']; ?>" class="edit-button">Edit</a>
                        <a href="manage_therapies.php?delete=<?php echo $therapy['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this therapy?')"
                            class="delete-button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>



</html>