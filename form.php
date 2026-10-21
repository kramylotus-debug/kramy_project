<?php
require_once 'connection.php';

// Fetch all registered students from the database
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching students: " . htmlspecialchars(mysqli_error($conn)));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 500px;
            margin: 20px auto 40px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            padding: 11px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .actions a {
            display: inline-block;
            margin: 3px;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 4px;
            color: white;
        }

        .view {
            background-color: #28a745;
        }

        .edit {
            background-color: #ffc107;
            color: #222 !important;
        }

        .delete {
            background-color: #dc3545;
        }

        .empty {
            text-align: center;
            color: #666;
        }

        @media (max-width: 650px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 12px;
                overflow-x: auto;
            }

            th, td {
                padding: 8px;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Student Registration System</h1>

    <!-- Student Registration Form -->
    <h2>Register a Student</h2>

    <form action="process.php" method="POST">

        <label for="first_name">First Name:</label>
        <input
            type="text"
            id="first_name"
            name="first_name"
            required
        >

        <label for="last_name">Last Name:</label>
        <input
            type="text"
            id="last_name"
            name="last_name"
            required
        >

        <label for="age">Age:</label>
        <input
            type="number"
            id="age"
            name="age"
            min="1"
            max="120"
            required
        >

        <button type="submit">Register Student</button>

    </form>

    <!-- Registered Students Table -->
    <h2>Registered Students</h2>

    <table>
        <thead>
            <tr>
                <th>SN</th>
                <th>First Name</th>
                <th>Second Name</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $sn = 1;

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                $id = (int) $row['id'];
                ?>

                <tr>
                    <td><?php echo $sn++; ?></td>

                    <td>
                        <?php echo htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars((string) $row['age'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>

                    <td class="actions">
                        <a
                            class="view"
                            href="view.php?id=<?php echo $id; ?>"
                        >View</a>

                        <a
                            class="edit"
                            href="edit.php?id=<?php echo $id; ?>"
                        >Edit</a>

                        <a
                            class="delete"
                            href="process.php?delete=<?php echo $id; ?>"
                            onclick="return confirm('Are you sure you want to delete this student?');"
                        >Delete</a>
                    </td>
                </tr>

                <?php
            }

        } else {
            ?>

            <tr>
                <td colspan="5" class="empty">
                    No students registered yet.
                </td>
            </tr>

            <?php
        }

        mysqli_free_result($result);
        ?>

        </tbody>
    </table>

</div>

</body>
</html>