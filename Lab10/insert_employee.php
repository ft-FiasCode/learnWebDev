<?php
include 'db.php';

// Get form data and sanitize
$cnic        = mysqli_real_escape_string($conn, trim($_POST['cnic']));
$full_name   = mysqli_real_escape_string($conn, trim($_POST['full_name']));
$father_name = mysqli_real_escape_string($conn, trim($_POST['father_name']));
$email       = mysqli_real_escape_string($conn, trim($_POST['email']));
$phone       = mysqli_real_escape_string($conn, trim($_POST['phone']));
$photo_name  = "";

// Handle photo upload
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = $_FILES['photo']['type'];
    $file_size = $_FILES['photo']['size'];

    if (!in_array($file_type, $allowed_types)) {
        header("Location: add_employee.php?error=Only+JPG,+PNG,+GIF+and+WebP+images+are+allowed.");
        exit;
    }

    if ($file_size > 2 * 1024 * 1024) {
        header("Location: add_employee.php?error=Photo+size+must+not+exceed+2MB.");
        exit;
    }

    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photo_name = uniqid('emp_', true) . '.' . $ext;
    $upload_path = 'uploads/' . $photo_name;

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
        header("Location: add_employee.php?error=Failed+to+upload+photo.+Check+uploads+folder+permissions.");
        exit;
    }
}

// Insert into database
$query = "INSERT INTO employees (cnic, full_name, father_name, email, phone, photo)
          VALUES ('$cnic', '$full_name', '$father_name', '$email', '$phone', '$photo_name')";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?msg=added&type=success");
} else {
    $error = mysqli_error($conn);
    // Clean up uploaded photo if DB insert failed
    if (!empty($photo_name) && file_exists('uploads/' . $photo_name)) {
        unlink('uploads/' . $photo_name);
    }
    header("Location: add_employee.php?error=" . urlencode("Database error: " . $error));
}
exit;
?>
