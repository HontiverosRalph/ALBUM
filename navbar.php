<?php
require_once 'core/dbConfig.php';
$currentPage = basename($_SERVER['PHP_SELF']);

$userId = $_SESSION['user_id'] ?? null; // Adjust session variable if necessary
$userName = "Bonnie Green";
$userEmail = "name@flowbite.com";

if ($userId) {
  $query = "SELECT first_name, last_name, email FROM user WHERE user_id = :id";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

  if ($stmt->execute()) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
      $userName = htmlspecialchars($user['first_name'] . " " . $user['last_name']);
      $userEmail = htmlspecialchars($user['email']);
    }
  }
}
?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
  <style>
    /* Enhancing profile dropdown */
    .dropdown:hover .dropdown-menu {
      display: block;
      opacity: 1;
      transition: opacity 0.3s ease-in-out;
    }

    .dropdown-menu {
      display: none;
      opacity: 0;
      position: absolute;
      top: 100%;
      left: 0;
      min-width: 180px;
      background-color: #1f2937;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      z-index: 100;
    }

    .profile-icon {
      background-color: #374151;
      color: white;
      font-size: 20px;
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      transition: background-color 0.3s;
    }

    .profile-icon:hover {
      background-color: #1d4ed8;
      cursor: pointer;
    }

    .dropdown-menu a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      border-bottom: 1px solid #444;
      transition: background-color 0.2s;
    }

    .dropdown-menu a:hover {
      background-color: #1d4ed8;
    }

    /* Add padding for top nav */
    .nav-links {
      padding-top: 10px;
    }
  </style>
</head>

<div style="padding-left: 20rem; padding-right: 20rem">
  <nav class="flex items-center justify-between px-4 py-3 bg-gray-800 dark:bg-gray-900 text-white">
    <!-- Left Side: Links -->
    <div class="w-full md:w-auto md:order-0 flex justify-between items-center">
      <!-- Toggle Button (Mobile View) -->
      <button type="button" class="block md:hidden text-gray-400 hover:bg-gray-700 dark:hover:bg-gray-600 rounded-lg p-2 nav-toggle"
        onclick="toggleNavbar()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
      </button>

      <!-- Links -->
      <div id="navbar-links" class="nav-links flex flex-col md:flex-row font-medium md:space-x-8 rtl:space-x-reverse md:mt-0">
        <ul class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-8 p-4 md:p-0 border border-gray-700 rounded-lg bg-gray-800 md:bg-transparent dark:bg-gray-900 dark:border-gray-700">
          <li><a href="index.php" class="block py-2 px-3 text-white rounded nav-link <?php echo ($currentPage == 'index.php') ? 'bg-blue-700' : ''; ?>">Home</a></li>
          <li><a href="dashboard.php" class="block py-2 px-3 text-white rounded nav-link <?php echo ($currentPage == 'dashboard.php') ? 'bg-blue-700' : ''; ?>">Dashboard</a></li>
          <li><a href="analytics.php" class="block py-2 px-3 text-white rounded nav-link <?php echo ($currentPage == 'analytics.php') ? 'bg-blue-700' : ''; ?>">Analytics</a></li>
        </ul>
      </div>
    </div>

    <!-- Right Side: Buttons and Dropdown -->
    <div class="flex items-center space-x-4">
      <!-- Add Record Button -->
      <a href="addAlbum.php">
        <button class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm hover:bg-blue-800 transition duration-300">Add a Album</button>
      </a>

      <!-- Search Bar -->
      <?php if ($currentPage === 'records.php') { ?>
        <div id="search-bar" class="relative">
          <form method="GET" action="records.php" onsubmit="return true;">
            <input type="text" name="search" id="search-navbar"
              class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Search record"
              value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
              oninput="toggleClearButton()" />
          </form>
          <button id="clear-btn" class="absolute inset-y-0 end-0 flex items-center pe-3 hidden" onclick="clearSearch()">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 6L14 14M6 14L14 6" />
            </svg>
          </button>
        </div>
      <?php } ?>

      <!-- Profile Dropdown -->
      <div class="relative dropdown">
        <button class="profile-icon flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
          id="user-menu-button">
          <span class="sr-only">Open user menu</span>
          <!-- No profile picture, use initials or icon -->
          <span class="text-lg font-bold"><?= strtoupper(substr($userName, 0, 1)) ?></span>
        </button>
        <div class="dropdown-menu absolute left-0 mt-2 w-48 bg-gray-800 text-white rounded-lg shadow-lg">
          <div class="px-4 py-3">
            <span class="block text-sm"><?= $userName ?></span>
            <span class="block text-sm font-medium text-gray-400 truncate"><?= $userEmail ?></span>
          </div>
          <ul class="py-2" aria-labelledby="user-menu-button">
            <li><a href="profile.php" class="block px-4 py-2 text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600">Profile</a></li>
            <li><a href="settings.php" class="block px-4 py-2 text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600">Settings</a></li>
            <li><hr class="border-gray-700 dark:border-gray-600"></li>
            <li><a href="signout.php" class="block px-4 py-2 text-sm text-white hover:bg-gray-700 dark:hover:bg-gray-600">Log out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</div>

<script>
  function toggleNavbar() {
    const navLinks = document.getElementById('navbar-links');
    navLinks.classList.toggle('active');
  }

  function toggleClearButton() {
    const searchInput = document.getElementById('search-navbar');
    const clearBtn = document.getElementById('clear-btn');
    clearBtn.style.display = searchInput.value ? 'flex' : 'none';
  }

  function clearSearch() {
    const searchInput = document.getElementById('search-navbar');
    searchInput.value = '';
    toggleClearButton();
  }
</script>
