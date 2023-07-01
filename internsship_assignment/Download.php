<?php
// Replace these values with your actual database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'health';

try {
  // Connect to the database
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Check if the ID parameter is set in the URL
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the SQL statement to fetch the health_report file name based on the user ID
    $sql = "SELECT health_report FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the health_report file name
    $healthReport = $stmt->fetchColumn();

    // Set the file path to the uploads directory
    $filePath = 'uploads/' . $healthReport;

    // Check if the file exists
    if (file_exists($filePath)) {
      // Set the appropriate headers to force download the file
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . $healthReport . '"');
      header('Content-Length: ' . filesize($filePath));
      readfile($filePath);
    } else {
      echo 'File not found.';
    }
  } else {
    echo 'Invalid request.';
  }
} catch (PDOException $e) {
  echo 'Database Error: ' . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
