<?php
require_once 'connection.php';

$id = filter_var(
    $_GET["id"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id || $id < 1) {
    die("Invalid student ID.");
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, first_name, last_name, age
     FROM students
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$student) {
    die("Student not found.");
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 550px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        p {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Student Details</h1>

    <p><strong>Student ID:</strong> <?= e($student['id']) ?></p>
    <p><strong>First Name:</strong> <?= e($student['first_name']) ?></p>
    <p><strong>Last Name:</strong> <?= e($student['last_name']) ?></p>
    <p><strong>Age:</strong> <?= e($student['age']) ?></p>

    <a href="form.php">Back to Student List</a>
    <a href="edit.php?id=<?= (int) $student['id'] ?>">Edit Student</a>
</div>

</body>
</html>