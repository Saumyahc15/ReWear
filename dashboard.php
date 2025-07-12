<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "rewear_db");
if ($conn->connect_error)
  die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$name = $user['name'];
$email = $user['email'];
$member_since = date('F Y', strtotime($user['created_at']));
$initials = strtoupper(substr($name, 0, 1) . substr(strstr($name, ' '), 1, 1));
$stmt->close();

// Stats
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM listings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$totalListings = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND status = 'delivered'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$totalSwaps = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND order_type = 'purchase'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$totalPurchases = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$avgRating = 4.8;
$points = rand(100, 300);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --accent-color: #C5A992;
      --secondary-color: #C5A992;
      --dark-color: #2f2f2f;
      --light-color: #F3F2EC;
      --body-text-color: #757575;
      --light-text-color: #afafaf;
      --dark-text-color: #2f2f2f;
    }

    body {
      font-family: "Raleway", arial, sans-serif;
      background: var(--light-color);
      min-height: 100vh;
      color: var(--dark-text-color);
    }

    .navbar {
      background: #f3f2ec;
      padding: 20px;
      border-bottom: 1px solid #E0E0E0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: bold;
      color: var(--accent-color);
      text-decoration: none;
    }

    .user-profile {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--accent-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .user-profile:hover {
      transform: scale(1.1);
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 2rem;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 2rem;
      margin-bottom: 2rem;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .user-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: var(--accent-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2rem;
      font-weight: bold;
    }

    .points-balance {
      background: var(--accent-color);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: bold;
      display: inline-block;
    }

    .analytics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s ease;
      border: 1px solid #EAE8DF;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      color: var(--accent-color);
      margin-bottom: 0.5rem;
      font-family: "Prata", Georgia, serif;
    }

    .stat-label {
      color: var(--body-text-color);
      font-size: 0.9rem;
    }

    .section {
      background: white;
      border-radius: 10px;
      padding: 2rem;
      margin-top: 2rem;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1.5rem;
    }

    .item-card {
      background: white;
      border: 1px solid #EAE8DF;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .item-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .item-image {
      width: 100%;
      height: 180px;
      /* Adjust height as needed */
      background: #EFEEE8;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .item-image img {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
      border-radius: 4px;
    }


    .item-info {
      padding: 1rem;
      text-align: center;
    }

    .item-title {
      font-weight: 500;
      color: var(--dark-text-color);
      margin-bottom: 0.5rem;
      font-size: 1rem;
      font-family: "Prata", Georgia, serif;
    }

    .item-price {
      color: green;
      font-weight: 600;
    }

    .item-status {
      padding: 0.3rem 0.8rem;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 500;
      display: inline-block;
    }

    .empty-state {
      padding: 2rem;
      text-align: center;
      color: var(--body-text-color);
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 1rem;
      }

      .dashboard-container {
        padding: 1rem;
      }

      .user-info {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }

      .section-header {
        flex-direction: column;
        gap: 1rem;
      }

      .items-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      }
    }
  </style>
</head>

<body>

  <div class="navbar">
    <div class="logo">ReWear</div>
    <div class="user-profile"><?= $initials ?></div>
  </div>

  <div class="dashboard-container">
    <div class="user-info">
      <div class="user-avatar"><?= $initials ?></div>
      <div>
        <h2><?= htmlspecialchars($name) ?></h2>
        <p>📧 <?= htmlspecialchars($email) ?></p>
        <p>📅 Member since <?= $member_since ?></p>
        <div class="points-balance">🏆 <?= $points ?> Points</div>
      </div>
    </div>

    <div class="analytics-grid">
      <div class="stat-card">
        <div class="stat-number"><?= $totalListings ?></div>
        <div class="stat-label">Items Listed</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= $totalSwaps ?></div>
        <div class="stat-label">Swaps</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= $totalPurchases ?></div>
        <div class="stat-label">Purchases</div>
      </div>
      <div class="stat-card">
        <div class="stat-number"><?= $avgRating ?></div>
        <div class="stat-label">Rating</div>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h3>My Listings</h3><a href="#">View All</a>
      </div>
      <div class="items-grid">
        <?php
        $stmt = $conn->prepare("SELECT title, price, image FROM listings WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
          echo '<div class="empty-state"><p>📦 No listings yet</p></div>';
        } else {
          while ($row = $res->fetch_assoc()) {
            $img = htmlspecialchars($row['image']);
            $title = htmlspecialchars($row['title']);
            $price = number_format($row['price'], 2);
            echo "
              <div class='item-card'>
                <div class='item-image'>
                  <img src='images/$img' onerror=\"this.src='images/jack.jpg'\" alt='$title' />
                </div>
                <div class='item-info'>
                  <h3 class='item-title'>$title</h3>
                  <p class='item-price'>$$price</p>
                </div>
              </div>";
          }
        }
        $stmt->close();
        ?>
      </div>
    </div>

    <div class="section">
      <div class="section-header">
        <h3>My Purchases</h3><a href="#">View All</a>
      </div>
      <div class="items-grid">
        <?php
        $stmt = $conn->prepare("SELECT item_name, status FROM orders WHERE user_id = ? AND order_type = 'purchase'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) {
          echo '<div class="empty-state"><p>🛍️ No purchases yet</p></div>';
        } else {
          while ($row = $res->fetch_assoc()) {
            $item = htmlspecialchars($row['item_name']);
            $status = htmlspecialchars($row['status']);
            echo "
              <div class='item-card'>
                <div class='item-image'>
                  <img src='booksaw-1.0.0/images/default-product.jpg' alt='$item' />
                </div>
                <div class='item-info'>
                  <h3 class='item-title'>$item</h3>
                  <p class='item-price'>--</p>
                  <div class='item-status'>$status</div>
                </div>
              </div>";
          }
        }
        $stmt->close();
        ?>
      </div>
    </div>
  </div>

  <script>
    document.querySelector(".user-profile").addEventListener("click", () => {
      alert("Profile dropdown coming soon!");
    });
  </script>
</body>

</html>