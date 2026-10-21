<?php
require_once 'connection.php';

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

$error = "";

$id = filter_var(
    $_POST["id"] ?? $_GET["id"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$id || $id < 1) {
    die("Invalid student ID.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $age = filter_var(
        $_POST["age"] ?? null,
        FILTER_VALIDATE_INT
    );

    if (
        $first_name === "" ||
        $last_name === "" ||
        $age === false ||
        $age === null ||
        $age < 1 ||
        $age > 120
    ) {
        $error = "Please enter valid student details.";
        $student = [
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "age" => $_POST["age"] ?? ""
        ];
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE students
             SET first_name = ?, last_name = ?, age = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssii",
            $first_name,
            $last_name,
            $age,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: form.php");
            exit;
        }

        mysqli_stmt_close($stmt);
        $error = "Unable to update student.";
    }

} else {

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 18px;
            cursor: pointer;
        }

        .error {
            color: #b00020;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Student</h1>

    <?php if ($error !== ""): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form action="edit.php" method="POST">
        <input
            type="hidden"
            name="id"
            value="<?= (int) $student['id'] ?>"
        >

        <label for="first_name">First Name:</label>
        <input
            type="text"
            id="first_name"
            name="first_name"
            value="<?= e($student['first_name']) ?>"
            required
        >

        <label for="last_name">Last Name:</label>
        <input
            type="text"
            id="last_name"
            name="last_name"
            value="<?= e($student['last_name']) ?>"
            required
        >

        <label for="age">Age:</label>
        <input
            type="number"
            id="age"
            name="age"
            min="1"
            max="120"
            value="<?= e($student['age']) ?>"
            required
        >

        <button type="submit">Update Student</button>
    </form>

    <p><a href="form.php">Back to Student List</a></p>
</div>

</body>
</html>