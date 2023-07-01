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

  // Prepare and execute the SQL statement to fetch user data
  $sql = "SELECT * FROM users";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  // Fetch all user data as an associative array
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Loop through the user data and display it in the table
  foreach ($users as $user) {
    echo '<tr>';
    echo '<td>' . $user['name'] . '</td>';
    echo '<td>' . $user['age'] . '</td>';
    echo '<td>' . $user['weight'] . '</td>';
    echo '<td>' . $user['email'] . '</td>';
    echo '<td>' . $user['health_report'] . '</td>';
    echo '<td><a href="download.php?id=' . $user['id'] . '">Download</a></td>';
    echo '</tr>';
  }
} catch (PDOException $e) {
  echo '<tr><td colspan="6">Database Error: ' . $e->getMessage() . '</td></tr>';
}

// Close the database connection
$conn = null;
?>
A