<?php
// Uncomment and configure your database connection
// $conn = new mysqli('localhost', 'username', 'password', 'database');
// if ($conn->connect_error) { die('Connection failed: ' . $conn->connect_error); }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $item_name = $_POST['item_name'];
//   $category = $_POST['category'];
//   $brand = $_POST['brand'];
//   $gender = $_POST['gender'];
//   $size = $_POST['size']; // Now a dropdown: XS, S, M, L, XL, XXL, Free Size
//   $price = $_POST['price'];
//   $description = $_POST['description'];
//   $image_path = '';
//
//   // Handle image upload
//   if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
//     $img_tmp = $_FILES['item_image']['tmp_name'];
//     $img_name = basename($_FILES['item_image']['name']);
//     $target_dir = 'uploads/';
//     if (!is_dir($target_dir)) mkdir($target_dir);
//     $target_file = $target_dir . uniqid() . '_' . $img_name;
//     if (move_uploaded_file($img_tmp, $target_file)) {
//       $image_path = $target_file;
//     }
//   }
//
//   // Insert into database (make sure your table has these columns)
//   $stmt = $conn->prepare('INSERT INTO clothes (item_name, category, brand, gender, size, price, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
//   $stmt->bind_param('sssssdss', $item_name, $category, $brand, $gender, $size, $price, $description, $image_path);
//   if ($stmt->execute()) {
//     // For AJAX: echo json_encode(['success' => true]);
//     // For normal form: header('Location: success.html');
//   } else {
//     // For AJAX: echo json_encode(['success' => false, 'error' => $stmt->error]);
//   }
//   $stmt->close();
//   $conn->close();
// }
?> 