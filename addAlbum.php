<?php
require_once 'core/dbConfig.php'; // Include dbConfig to access $pdo

if (isset($_POST['submit'])) {
  // Get description and title from the form
  $description = $_POST['description'];
  $title = $_POST['title'];

  // Get file name and temporary name
  $fileName = $_FILES['photo']['name'];
  $tempFileName = $_FILES['photo']['tmp_name'];

  // Get file extension
  $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

  // Generate a unique ID for the image name
  $uniqueID = sha1(md5(rand(1, 9999999)));

  // Combine the unique ID with the file extension to create a unique image name
  $imageName = $uniqueID . "." . $fileExtension;

  try {
    // Insert image details into the database using $pdo
    $query = "INSERT INTO photos (photo_name, description, title) VALUES (:photo_name, :description, :title)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':photo_name', $imageName);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':title', $title);
    $stmt->execute();

    // Get the user ID from session (assuming you're using sessions for user login)
    session_start();
    $userId = $_SESSION['user_id'] ?? null;

    // Log the action if the user is logged in
    if ($userId) {
      $logQuery = "INSERT INTO activity_log (user_id, action, record_id) VALUES (?, ?, ?)";
      $logStmt = $pdo->prepare($logQuery);
      $logStmt->execute([$userId, 'Added New Photo', $pdo->lastInsertId()]);
    }

    // Ensure the uploads folder exists and is writable
    $folder = "uploads/" . $imageName;

    if (!is_dir('uploads')) {
      mkdir('uploads', 0755, true); // Create uploads folder if it doesn't exist
    }

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($tempFileName, $folder)) {
      header("Location: index.php"); // Redirect after successful upload
      exit();
    } else {
      echo "Error uploading file. Make sure the uploads folder has proper permissions.";
    }
  } catch (PDOException $e) {
    echo "Error saving photo to the database: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Photo</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white flex justify-center items-center min-h-screen">

  <div class="bg-gradient-to-r from-indigo-700 via-purple-800 to-indigo-900 p-8 rounded-xl shadow-2xl w-full max-w-md transition-all duration-500">
    <h2 class="text-4xl font-extrabold text-center mb-6 text-white">Upload Photo</h2>

    <form action="" method="POST" enctype="multipart/form-data">

      <div class="mb-6">
        <label for="title" class="block text-sm font-medium text-gray-300">Title:</label>
        <textarea name="title" id="title" rows="4"
          class="w-full px-5 py-3 mt-1 border border-gray-600 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white transition-all ease-in-out duration-300"
          placeholder="What's on your mind?" required></textarea>
      </div>

      <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-300">Description:</label>
        <textarea name="description" id="description" rows="4"
          class="w-full px-5 py-3 mt-1 border border-gray-600 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-800 text-white transition-all ease-in-out duration-300"
          required></textarea>
      </div>

      <div class="mb-6">
        <label for="photo" class="block text-sm font-medium text-gray-300">Choose Image:</label>
        <input type="file" name="photo" id="photo" accept="image/*"
          class="w-full text-gray-500 font-medium text-sm bg-gray-800 file:cursor-pointer cursor-pointer file:border-0 file:py-3 file:px-5 file:mr-4 file:bg-gray-700 file:hover:bg-gray-600 file:text-white rounded-lg transition-all ease-in-out duration-300"
          required>
      </div>

      <div class="text-center">
        <input type="submit" name="submit" value="Post Album"
          class="w-full py-3 px-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:bg-gradient-to-l focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all ease-in-out duration-300 transform hover:scale-105">
      </div>
    </form>
  </div>

</body>

</html>
