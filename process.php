<?php
require_once 'connection.php';

// Register a new student
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
        die("Please enter valid student details.");
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO students (first_name, last_name, age)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $first_name,
        $last_name,
        $age
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: form.php");
        exit;
    }

    mysqli_stmt_close($stmt);
    die("Unable to register student.");
}

// Delete a student (POST only)
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["delete_id"])
) {
    $id = filter_var(
        $_POST["delete_id"],
        FILTER_VALIDATE_INT
    );

    if (!$id || $id < 1) {
        die("Invalid student ID.");
    }

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM students WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: form.php");
    exit;
}

http_response_code(405);
echo "Invalid request.";
?>
