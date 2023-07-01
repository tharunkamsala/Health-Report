<?php
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$dbname = 'health'; 

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Prepare the SQL statement to insert user details
  $sql = "INSERT INTO users (name, age, weight, email,report) VALUES (:name, :age, :weight, :email,:report)";
  $stmt = $conn->prepare($sql);

  // Bind the form data to the placeholders in the SQL statement
  $stmt->bindParam(':name', $_POST['name']);
  $stmt->bindParam(':age', $_POST['age']);
  $stmt->bindParam(':weight', $_POST['weight']);
  $stmt->bindParam(':email', $_POST['email']);
  $stmt->bindParam(':report', $_POST['Report']);

  // Execute the SQL statement
  $stmt->execute();

  // Get the ID of the inserted user for using it to store the uploaded file with a unique name
  $userId = $conn->lastInsertId();

  // Handle the uploaded PDF file
  $targetDir = 'uploads/';
  $targetFile = $targetDir . basename($_FILES['healthReport']['name']);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Check if the file is a PDF
  if ($fileType !== 'pdf') {
    echo json_encode(array('status' => 'error', 'message' => 'Only PDF files are allowed.'));
    exit;
  }

  // Generate a unique name for the uploaded file using the user ID
  $newFileName = $userId . '.pdf';
  $newFilePath = $targetDir . $newFileName;

  // Move the uploaded file to the desired location
  if (move_uploaded_file($_FILES['healthReport']['tmp_name'], $newFilePath)) {
    // File uploaded successfully, you can now save the file path in the database if needed.
    $reportPath = $newFilePath; // Store the file path in a variable
    $updateSql = "UPDATE users SET health_report = :reportPath WHERE id = :userId"; // Modify the SQL statement to add the health_report column
    $stmt = $conn->prepare($updateSql);
    $stmt->bindParam(':reportPath', $reportPath);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    echo json_encode(array('status' => 'success', 'message' => 'User details and health report uploaded successfully.'));
  } else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to upload health report.'));
  }
} catch (PDOException $e) {
  echo json_encode(array('status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()));
}

// Close the database connection
$conn = null;
?>
