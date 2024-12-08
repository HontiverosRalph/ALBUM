<?php
require_once 'core/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect and sanitize user input
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  // Prepare the SQL query to check the user credentials
  $query = "SELECT * FROM user WHERE username = :username";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    // If password is correct, start the session and redirect
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    header("Location: index.php"); // Redirect to dashboard
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
    };
  </script>
</head>

<body class="bg-gray-900 dark:bg-gray-900 text-white">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div
      class="border border-gray-700 dark:border-gray-600 rounded-xl p-8 max-w-md shadow-xl w-full bg-gray-800 dark:bg-gray-800 transition-all ease-in-out duration-300">
      <form method="POST" class="space-y-6">
        <div class="mb-6">
          <h3 class="text-gray-100 dark:text-gray-200 text-3xl font-extrabold text-center">Sign in</h3>
        </div>
        <div>
          <label for="username" class="text-gray-300 dark:text-gray-200 text-sm mb-2 block">Username</label>
          <div class="relative flex items-center">
            <input id="username" name="username" type="text" required
              class="w-full text-sm text-gray-100 dark:text-gray-200 dark:bg-gray-700 border border-gray-600 dark:border-gray-500 px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-blue-600 focus:text-gray-900 dark:focus:text-white transition-all duration-200"
              placeholder="Enter user name" />
          </div>
        </div>
        <div>
          <label for="password" class="text-gray-300 dark:text-gray-200 text-sm mb-2 block">Password</label>
          <div class="relative flex items-center">
            <input id="password" name="password" type="password" required
              class="w-full text-sm text-gray-100 dark:text-gray-200 dark:bg-gray-700 border border-gray-600 dark:border-gray-500 px-4 py-3 rounded-lg outline-none focus:ring-2 focus:ring-blue-600 focus:text-gray-900 dark:focus:text-white transition-all duration-200"
              placeholder="Enter password" />
          </div>
        </div>
        <div class="mt-6">
          <button type="submit"
            class="w-full shadow-lg py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition duration-300 ease-in-out transform hover:scale-105 dark:bg-blue-700 dark:hover:bg-blue-800">
            Log in
          </button>
        </div>
        <p class="text-sm mt-6 text-center text-gray-300 dark:text-gray-400">
          Don't have an account?
          <a href="register.php"
            class="text-blue-400 dark:text-blue-500 font-semibold hover:underline ml-1">Register here</a>
        </p>
        <?php if (isset($error)): ?>
          <p class="text-sm text-red-500 text-center mt-4"><?php echo $error; ?></p>
        <?php endif; ?>
      </form>
    </div>
  </div>
</body>

</html>
