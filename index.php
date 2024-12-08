<?php
require_once 'core/dbConfig.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: login.php");
  exit();
}

// Query to get all the photos from the database
$query = "SELECT * FROM photos";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch the result
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <style>
    /* General Dark Mode Styles */
    body {
      background-color: #121212;
      color: #e0e0e0;
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }

    header {
      background-color: #1e1e1e;
      padding: 40px;
      text-align: center;
      border-bottom: 3px solid #333;
    }

    h1, h2 {
      color: #e0e0e0;
    }

    /* Main Container */
    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 16px;
    }

    /* Grid for photos */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      padding: 0 10px;
    }

    /* Individual photo cards */
    .photo-card {
      background-color: #1e1e1e;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    }

    .photo-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
      background-color: #333;
    }

    .photo-card img {
      border-radius: 10px;
      width: 100%;
      height: 200px;
      object-fit: cover;
      margin-bottom: 16px;
      transition: transform 0.3s ease;
    }

    .photo-card img:hover {
      transform: scale(1.05);
    }

    .photo-card h3 {
      font-size: 1.2rem;
      font-weight: bold;
      color: #e0e0e0;
      margin-bottom: 8px;
    }

    .photo-card p {
      font-size: 1rem;
      color: #b0b0b0;
      margin-bottom: 12px;
    }

    .action-links {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 10px;
    }

    .action-links a {
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 25px;
      font-weight: bold;
      color: #ffffff;
      background-color: #1e88e5;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .action-links a:hover {
      background-color: #0d47a1;
      transform: translateY(-2px);
    }

    .action-links .delete {
      background-color: #e53935;
    }

    .action-links .delete:hover {
      background-color: #b71c1c;
      transform: translateY(-2px);
    }

    /* Center alignment for empty result message */
    .text-center {
      text-align: center;
      color: #f44336;
    }

    /* Adjusting text in header */
    header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    header h2 {
      font-size: 1.5rem;
    }
  </style>
</head>

<body>
  <header>
    <h1>Welcome to the Photo Gallery</h1>
    <h2>Browse and manage your uploaded photos</h2>
  </header>

  <?php include 'navbar.php'; ?>

  <div class="container">
    <?php if (empty($result)) { ?>
      <h1 class="text-center">No photos uploaded, be the first one!</h1>
    <?php } else { ?>
      <h1>Uploaded Photos</h1>
    <?php } ?>

    <div class="grid">
      <?php foreach ($result as $row) { ?>
        <div class="photo-card">
          <img src="uploads/<?php echo htmlspecialchars($row['photo_name']); ?>" alt="Photo">
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>

          <div class="action-links">
            <!-- Edit Button -->
            <a href="editAlbum.php?id=<?php echo $row['photo_id']; ?>" class="edit">Edit</a>
            <!-- Delete Button -->
            <a href="deleteAlbum.php?id=<?php echo $row['photo_id']; ?>" class="delete">Delete</a>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</body>

</html>
