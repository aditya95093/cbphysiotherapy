


<?php
require ('includes/connection.inc.php');
require ('function.inc.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapies Table </title>
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
            width: 100%;
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
        <h2>Therapies Table </h2>

        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            <?php
            $therapiesQuery = "SELECT * FROM therapies";
            $result = mysqli_query($con, $therapiesQuery);

            if ($result) {
                $therapies = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($therapies as $therapy):
                    ?>
                    <tr>
                        <td>
                            <?php echo $therapy['title']; ?>
                        </td>
                        <td>
                            <?php echo $therapy['description']; ?>
                        </td>
                        <td>
                            <img src="<?php echo $therapy['image_url']; ?>" alt="Therapy Image" width="130">
                        </td>

                        <td>
                            <?php echo getCategoryName($con, $therapy['category_id']); ?>
                        </td>
                        <td>
                            <div class="container1">
                                <a href="edit.php?id=<?php echo $therapy['id']; ?>" class="edit-button">Edit</a>
                                <a href="manage_therapies.php?delete=<?php echo $therapy['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this therapy?')"
                                    class="delete-button">Delete</a>
                            </div>

                        </td>
                    </tr>
                    <?php
                endforeach;
            } else {
                die ("Error fetching therapies: " . mysqli_error($con));
            }
            ?>
        </table>
    </div>
</body>

</html>