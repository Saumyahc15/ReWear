<?php
require 'db.php';

error_reporting(0);
$userId = intval($_GET['id']);


// Delete user
// Handle delete request
if (isset($_GET['delete_user']) && is_numeric($_GET['delete_user'])) {
	$delete_id = intval($_GET['delete_user']);

	// Optional: delete their listings too if needed
	$stmt1 = $conn->prepare("DELETE FROM listings WHERE user_id = ?");
	$stmt1->bind_param("i", $delete_id);
	$stmt1->execute();
	$stmt1->close();

	// Optional: delete their orders too
	$stmt2 = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
	$stmt2->bind_param("i", $delete_id);
	$stmt2->execute();
	$stmt2->close();

	// Delete user
	$stmt3 = $conn->prepare("DELETE FROM users WHERE id = ?");
	$stmt3->bind_param("i", $delete_id);
	$stmt3->execute();
	$stmt3->close();

	// Redirect to avoid resubmission
	header("Location: index.php?deleted=1");
	exit;
}


// Handle user update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
	$id = intval($_POST['id']);
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);

	$stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
	$stmt->bind_param("ssi", $name, $email, $id);
	$stmt->execute();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
	$order_id = intval($_POST['order_id']);
	$item_name = trim($_POST['item_name']);
	$order_type = $_POST['order_type'];
	$status = $_POST['status'];

	$stmt = $conn->prepare("UPDATE orders SET item_name = ?, order_type = ?, status = ? WHERE id = ?");
	$stmt->bind_param("sssi", $item_name, $order_type, $status, $order_id);
	$stmt->execute();

	header("Location: index.php?order_updated=1");
	exit;
}
// Handle listing update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_listing'])) {
	$id = intval($_POST['listing_id']);
	$title = trim($_POST['title']);
	$price = floatval($_POST['price']);

	if (!empty($_FILES['image']['name'])) {
		$targetDir = "images/";
		$filename = time() . "_" . basename($_FILES["image"]["name"]);
		$targetFile = $targetDir . $filename;
		move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

		$stmt = $conn->prepare("UPDATE listings SET title = ?, price = ?, image = ? WHERE id = ?");
		$stmt->bind_param("sdsi", $title, $price, $filename, $id);
	} else {
		$stmt = $conn->prepare("UPDATE listings SET title = ?, price = ? WHERE id = ?");
		$stmt->bind_param("sdi", $title, $price, $id);
	}

	$stmt->execute();


}

?>


<!DOCTYPE html>

<html lang="en">

<head>
	<title>BookSaw - Free Book Store HTML CSS Template</title>
	<meta charset="utf-8" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="telephone=no" name="format-detection" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="" name="author" />
	<meta content="" name="keywords" />
	<meta content="" name="description" />
	<link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
		integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" rel="stylesheet" />
	<link href="css/normalize.css" rel="stylesheet" type="text/css" />
	<link href="icomoon/icomoon.css" rel="stylesheet" type="text/css" />
	<link href="css/vendor.css" rel="stylesheet" type="text/css" />
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<style type="text/css">
	.uniform-image {
		width: 400px;
		height: 400px;
		object-fit: cover;
		border-radius: 8px;
		box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
	}
</style>
<script>
	function toggleEditForm(userId) {
		const form = document.getElementById('edit-form-' + userId);
		form.style.display = form.style.display === 'none' ? 'block' : 'none';
	}
</script>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">
	<div id="header-wrap">
		<div class="top-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="social-links">
							<ul>
								<li>
									<a href="#"><i class="icon icon-facebook"></i></a>
								</li>
								<li>
									<a href="#"><i class="icon icon-twitter"></i></a>
								</li>
								<li>
									<a href="#"><i class="icon icon-youtube-play"></i></a>
								</li>
								<li>
									<a href="#"><i class="icon icon-behance-square"></i></a>
								</li>
							</ul>
						</div><!--social-links-->
					</div>
					<div class="col-md-6">
						<div class="right-element">
							<a class="user-account for-buy" href="#"><i
									class="icon icon-user"></i><span>Account</span></a>

							<div class="action-menu">
								<div class="search-bar">
									<a class="user-account for-buy" href="#"><i
											class="icon icon-user"></i><span>Account</span></a>

									</form>
								</div>
							</div>
						</div><!--top-right-->
					</div>
				</div>
			</div>
		</div><!--top-content-->
		<header id="header">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-2">
						<span style="font-family: 'Georgia', serif; font-size: 2.2rem; letter-spacing: 1px;">
							<strong style="color: #3e3e3e;">Re-</strong><span style="color: #7c5c2b;">Wear</span>

						</span>

					</div>
					<div class="col-md-10">
						<nav id="navbar">
							<div class="main-menu stellarnav">
								<ul class="menu-list">
									<li class="menu-item active"><a href="#home"> </a></li>

									<li class="menu-item"><a class="nav-link" href="#featured-books">Manage Users</a>
									</li>
									<li class="menu-item"><a class="nav-link" href="#popular-books">Manage Orders</a>
									</li>
									<li class="menu-item"><a class="nav-link" href="#special-offer">Manage Listings</a>
									</li>
									<ul>
										<li><a href="index.php">About</a></li>
										<li><a href="index.php">Styles</a></li>
										<li><a href="index.php">Blog</a></li>
										<li><a href="index.php">Post Single</a></li>
										<li><a href="index.php">Our Store</a></li>
										<li><a href="index.php">Product Single</a></li>
										<li><a href="index.php">Contact</a></li>
										<li><a href="index.php">Thank You</a></li>

									</ul>
									<div class="hamburger">
										<span class="bar"></span>
										<span class="bar"></span>
										<span class="bar"></span>
									</div>
							</div>
						</nav>
					</div>
				</div>
			</div>
		</header>
	</div>
	<section id="billboard">

		<div class="container">
			<div class="row">
				<div class="col-md-12">



					<div class="main-slider pattern-overlay">
						<div class="slider-item">
							<div class="banner-content">
								<h2 class="banner-title">Welcome to Re-Wear Admin</h2>
								<p>Track listings, manage users, and keep your sustainable fashion marketplace running
									smoothly. Get started today.</p>
								<div class="btn-wrap">
									<a href="#"></a>

								</div>
							</div><!--banner-content-->
							<img src="images/logo.jpg" alt="banner" class="banner-image">
						</div><!--slider-item-->
					</div>
				</div>
			</div>

	</section>

	<section class="py-5 my-5" id="featured-books">
		<div class="container">
			<div class="section-header align-center mb-4">
				<div class="title"><span>Users</span></div>
				<h2 class="section-title">Manage Users</h2>
			</div>

			<div class="row g-4">
				<?php
				$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

				if ($users->num_rows > 0):
					while ($user = $users->fetch_assoc()):
						$userId = $user['id'];
						$imageFile = !empty($user['profile_image']) ? $user['profile_image'] : 'default-user.png';

						$stmt = $conn->prepare("SELECT * FROM listings WHERE user_id = ?");
						$stmt->bind_param("i", $userId);
						$stmt->execute();
						$listings = $stmt->get_result();
						?>
						<div class="col-12 user-block" id="user-block-<?= $userId ?>">
							<div
								class="d-flex align-items-center justify-content-between p-4 shadow-sm rounded bg-white border-start border-4 border-warning">
								<div class="d-flex align-items-center">
									<img src="images/<?= htmlspecialchars($imageFile) ?>" class="rounded-circle me-4" width="70"
										height="70" style="object-fit: cover;" alt="User">
									<div>
										<h5 class="mb-1 fw-bold"><?= htmlspecialchars($user['name']) ?></h5>
										<p class="mb-0 text-muted">
											<?= htmlspecialchars($user['email']) ?><br>
											<small>Registered: <?= date("M d, Y", strtotime($user['created_at'])) ?></small>
										</p>
									</div>
								</div>
								<div class="text-end d-flex flex-column align-items-end">
									<button class="btn btn-sm btn-outline-accent px-4 mb-2"
										onclick="toggleUserDetails(<?= $userId ?>)">Edit</button>
									<a href="index.php?delete_user=<?= $userId ?>"
										class="btn btn-sm btn-outline-secondary px-4"
										onclick="return confirm('Delete this user?')">Delete</a>


								</div>
							</div>

							<div id="user-details-<?= $userId ?>" style="display: none;" class="mt-3">
								<!-- User Edit Form -->
								<form method="POST" class="p-3 bg-light rounded border mb-3">
									<input type="hidden" name="id" value="<?= $userId ?>">
									<input type="hidden" name="update_user" value="1">
									<div class="row g-2 align-items-center">
										<div class="col-md-5">
											<input type="text" name="name" class="form-control"
												value="<?= htmlspecialchars($user['name']) ?>" required>
										</div>
										<div class="col-md-5">
											<input type="email" name="email" class="form-control"
												value="<?= htmlspecialchars($user['email']) ?>" required>
										</div>
										<div class="col-md-2 d-grid">
											<button type="submit" class="btn btn-outline-accent">Save</button>
										</div>
									</div>
								</form>

								<!-- Listings Edit Form -->
								<?php if ($listings->num_rows > 0): ?>
									<h6 class="ps-2 text-muted">Listings by <?= htmlspecialchars($user['name']) ?>:</h6>
									<div class="row">
										<?php while ($listing = $listings->fetch_assoc()): ?>
											<div class="col-md-6 col-lg-4 mb-4">
												<form method="POST" enctype="multipart/form-data"
													class="bg-white shadow-sm p-3 rounded border h-100">
													<input type="hidden" name="listing_id" value="<?= $listing['id'] ?>">
													<input type="hidden" name="update_listing" value="1">
													<img src="images/<?= htmlspecialchars($listing['image']) ?>"
														class="w-100 rounded mb-2" style="height: 160px; object-fit: cover;"
														alt="Listing">
													<div class="mb-2">
														<label class="form-label mb-0">Title</label>
														<input type="text" name="title" class="form-control form-control-sm"
															value="<?= htmlspecialchars($listing['title']) ?>" required>
													</div>
													<div class="mb-2">
														<label class="form-label mb-0">Price (RS)</label>
														<input type="number" step="0.01" name="price"
															class="form-control form-control-sm" value="<?= $listing['price'] ?>"
															required>
													</div>
													<div class="mb-2">
														<label class="form-label mb-0">Image</label>
														<input type="file" name="image" class="form-control form-control-sm">
													</div>
													<div class="d-grid">
														<button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
													</div>
												</form>
											</div>
										<?php endwhile; ?>
									</div>
								<?php else: ?>
									<p class="ps-2 text-muted">No listings available.</p>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; else: ?>
					<div class="col-12 text-center">
						<p class="text-muted">No users found.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="bookshelf py-5 my-5" id="popular-books">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="section-header align-center">
						<div class="title"><span>Orders</span></div>
						<h2 class="section-title">Manage Orders</h2>
					</div>

					<ul class="tabs">
						<li class="active tab" data-tab-target="#all-orders">All</li>
						<li class="tab" data-tab-target="#pending-orders">Pending</li>
						<li class="tab" data-tab-target="#shipped-orders">Shipped</li>
						<li class="tab" data-tab-target="#delivered-orders">Delivered</li>
						<li class="tab" data-tab-target="#cancelled-orders">Cancelled</li>
					</ul>

					<div class="tab-content">
						<?php
						$statuses = ['All' => '', 'Pending' => 'Pending', 'Shipped' => 'Shipped', 'Delivered' => 'Delivered', 'Cancelled' => 'Cancelled'];

						foreach ($statuses as $key => $status):
							$tabId = strtolower($key) . '-orders';
							$isActive = ($key === 'All') ? 'active' : '';
							echo "<div class='$isActive' data-tab-content id='$tabId'><div class='row g-4'>";

							$sql = "SELECT orders.*, users.name FROM orders 
								JOIN users ON orders.user_id = users.id ";
							if ($status !== '') {
								$sql .= "WHERE orders.status = '$status' ";
							}
							$sql .= "ORDER BY orders.created_at DESC";
							$result = $conn->query($sql);

							if ($result->num_rows > 0):
								while ($order = $result->fetch_assoc()):
									$orderId = $order['id'];
									?>
									<div class="col-md-6">
										<div class="product-item p-4 shadow-sm rounded bg-white">
											<h5 class="fw-bold">Order #<?= str_pad($orderId, 5, '0', STR_PAD_LEFT) ?></h5>
											<p class="text-muted mb-1">User: <?= htmlspecialchars($order['name']) ?></p>
											<p class="text-muted mb-1">Item: <?= htmlspecialchars($order['item_name']) ?></p>
											<p class="text-muted mb-1">Type: <?= htmlspecialchars($order['order_type']) ?></p>
											<p class="text-muted mb-1">Status: <strong><?= $order['status'] ?></strong></p>
											<p class="text-muted mb-3">Placed:
												<?= date('M d, Y', strtotime($order['created_at'])) ?>
											</p>
											<div>
												<button class="btn btn-outline-accent btn-sm me-2"
													onclick="toggleOrderForm(<?= $orderId ?>)">Update</button>
											</div>
										</div>

										<!-- Hidden Update Form -->
										<form method="POST" class="mt-2 p-3 bg-light border rounded" id="order-form-<?= $orderId ?>"
											style="display: none;">
											<input type="hidden" name="order_id" value="<?= $orderId ?>">
											<div class="mb-2">
												<label class="form-label">Item Name</label>
												<input type="text" name="item_name" class="form-control form-control-sm"
													value="<?= htmlspecialchars($order['item_name']) ?>" required>
											</div>
											<div class="mb-2">
												<label class="form-label">Order Type</label>
												<select name="order_type" class="form-select form-select-sm">
													<option value="swap" <?= $order['order_type'] == 'swap' ? 'selected' : '' ?>>Swap
													</option>
													<option value="redeem" <?= $order['order_type'] == 'redeem' ? 'selected' : '' ?>>
														Redeem</option>
												</select>
											</div>
											<div class="mb-3">
												<label class="form-label">Status</label>
												<select name="status" class="form-select form-select-sm">
													<?php foreach (['Pending', 'Shipped', 'Delivered', 'Cancelled'] as $s): ?>
														<option value="<?= $s ?>" <?= $order['status'] == $s ? 'selected' : '' ?>><?= $s ?>
														</option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="d-grid">
												<button type="submit" class="btn btn-outline-accent btn-sm">Save Changes</button>
											</div>
										</form>
									</div>
									<?php
								endwhile;
							else:
								echo "<div class='col-12 text-muted'><p>No " . ($status ?: 'orders') . " found.</p></div>";
							endif;
							echo "</div></div>";
						endforeach;
						?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script>
		function toggleOrderForm(id) {
			const el = document.getElementById('order-form-' + id);
			el.style.display = el.style.display === 'none' || el.style.display === '' ? 'block' : 'none';
		}
	</script>
	<section class="align-center pb-5 mb-5" id="quotation">
		<div class="inner-content">
			<h2 class="section-title divider">Quote of the Day</h2>
			<blockquote data-aos="fade-up">
				<q>“Fashion doesn’t have to come at the cost of the planet. Reuse, restyle, and rewear.”</q>
				<div class="author-name">— Re-Wear Initiative</div>
			</blockquote>
		</div>
	</section>
	<section class="bookshelf pb-5 mb-5" id="special-offer">
		<div class="section-header align-center">
			<div class="title"><span>Inventory Control</span></div>
			<h2 class="section-title">Manage Listings</h2>
		</div>
		<div class="container">
			<div class="row">
				<div class="inner-content">
					<div class="product-list" data-aos="fade-up">
						<div class="grid product-grid">
							<?php
							$query = "SELECT l.*, u.name AS seller_name 
                                  FROM listings l
                                  JOIN users u ON l.user_id = u.id
                                  ORDER BY l.created_at DESC";
							$result = $conn->query($query);

							if ($result->num_rows > 0):
								while ($row = $result->fetch_assoc()):
									?>
									<div class="product-item">
										<figure class="product-style">
											<img src="images/<?= htmlspecialchars($row['image']) ?>" alt="Product"
												class="product-item uniform-image">
										</figure>
										<figcaption>
											<h3><?= htmlspecialchars($row['title']) ?></h3>
											<span>Seller: <?= htmlspecialchars($row['seller_name']) ?></span>
											<div class="item-price">Rs <?= number_format($row['price'], 2) ?></div>
											<div class="mt-2">
												<a href="edit_listing.php?id=<?= $row['id'] ?>"
													class="btn btn-outline-accent btn-sm me-2">Edit</a>
												<a href="delete_listing.php?id=<?= $row['id'] ?>"
													class="btn btn-outline-secondary btn-sm"
													onclick="return confirm('Delete this listing?');">Delete</a>
											</div>
										</figcaption>
									</div>
								<?php endwhile; else: ?>
								<p class="text-muted">No listings found.</p>
							<?php endif; ?>
						</div><!--grid-->
					</div>
				</div><!--inner-content-->
			</div>
		</div>
	</section>

	<section id="follow-instagram">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-6">
							<div class="title-element">
								<h2 class="section-title divider">Follow Us on Instagram</h2>
							</div>
						</div>
						<div class="col-md-6">
							<div class="subscribe-content" data-aos="fade-up">
								<p>Join our vibrant Re-Wear community on Instagram for latest drops, styling tips, and
									more!</p>
								<a href="https://www.instagram.com/rewear_closet/" target="_blank" class="btn-subscribe"
									style="text-decoration: none;">
									<span>@rewear_closet</span>
									<i class="icon icon-instagram"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="py-5 my-5" id="latest-blog">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-header align-center">
						<div class="title">
							<span>Explore sustainable fashion</span>
						</div>
						<h2 class="section-title">Latest from Re-Wear</h2>
					</div>
					<div class="row">

						<div class="col-md-4">
							<article class="column" data-aos="fade-up">
								<figure>
									<a class="image-hvr-effect" href="#">
										<img alt="post" class="post-image" src="images/i1.jpg" />
									</a>
								</figure>
								<div class="post-item">
									<div class="meta-date">Jul 12, 2025</div>
									<h3><a href="#">5 Reasons Why Reusing Clothes Matters</a></h3>
									<p>Learn why second-hand fashion is more than just a trend—it's a movement for the
										planet.</p>
									<a href="#" class="btn btn-sm btn-outline-accent mt-2">Read More</a>
								</div>
							</article>
						</div>

						<div class="col-md-4">
							<article class="column" data-aos="fade-up" data-aos-delay="200">
								<figure>
									<a class="image-hvr-effect" href="#">
										<img alt="post" class="post-image" src="images/thrift.jpg" />
									</a>
								</figure>
								<div class="post-item">
									<div class="meta-date">Jul 6, 2025</div>
									<h3><a href="#">How to Style Thrifted Outfits Like a Pro</a></h3>
									<p>Give your wardrobe a new life with smart styling ideas using preloved pieces.</p>
									<a href="#" class="btn btn-sm btn-outline-accent mt-2">Read More</a>
								</div>
							</article>
						</div>

						<div class="col-md-4">
							<article class="column" data-aos="fade-up" data-aos-delay="400">
								<figure>
									<a class="image-hvr-effect" href="#">
										<img alt="post" class="post-image" src="images/circular.jpg" />
									</a>
								</figure>
								<div class="post-item">
									<div class="meta-date">Jun 28, 2025</div>
									<h3><a href="#">Circular Fashion: What It Really Means</a></h3>
									<p>Understand the principles of circular fashion and how Re-Wear aligns with it.</p>
									<a href="#" class="btn btn-sm btn-outline-accent mt-2">Read More</a>
								</div>
							</article>
						</div>

					</div>

					<div class="row">
						<div class="btn-wrap align-center mt-4">
							<a class="btn btn-outline-accent btn-accent-arrow" href="#">View All Articles <i
									class="icon icon-ns-arrow-right"></i></a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>
	<footer id="footer">
		<div class="container">
			<div class="row">
				<!-- Logo and About -->
				<div class="col-md-4">
					<div class="footer-item">
						<div class="company-brand">
							<img alt="Re-Wear Logo" class="footer-logo" src="images/rewear-logo.png" />
							<p>Re-Wear is your conscious fashion companion. We promote sustainability by encouraging
								reuse, restyle, and responsible fashion choices.</p>
						</div>
					</div>
				</div>

				<!-- About Re-Wear -->
				<div class="col-md-2">
					<div class="footer-menu">
						<h5>About Re-Wear</h5>
						<ul class="menu-list">
							<li class="menu-item"><a href="#">Our Story</a></li>
							<li class="menu-item"><a href="#">Sustainability</a></li>
							<li class="menu-item"><a href="#">Team</a></li>
							<li class="menu-item"><a href="#">Careers</a></li>
							<li class="menu-item"><a href="#">Press</a></li>
						</ul>
					</div>
				</div>

				<!-- Explore -->
				<div class="col-md-2">
					<div class="footer-menu">
						<h5>Explore</h5>
						<ul class="menu-list">
							<li class="menu-item"><a href="#">Home</a></li>
							<li class="menu-item"><a href="#">Manage Listings</a></li>
							<li class="menu-item"><a href="#">Manage Users</a></li>
							<li class="menu-item"><a href="#">Manage Orders</a></li>
							<li class="menu-item"><a href="#">Articles</a></li>
						</ul>
					</div>
				</div>

				<!-- My Account -->
				<div class="col-md-2">
					<div class="footer-menu">
						<h5>My Account</h5>
						<ul class="menu-list">
							<li class="menu-item"><a href="#">Login</a></li>
							<li class="menu-item"><a href="#">My Closet</a></li>
							<li class="menu-item"><a href="#">Wishlist</a></li>
							<li class="menu-item"><a href="#">Order History</a></li>
						</ul>
					</div>
				</div>

				<!-- Support -->
				<div class="col-md-2">
					<div class="footer-menu">
						<h5>Support</h5>
						<ul class="menu-list">
							<li class="menu-item"><a href="#">FAQs</a></li>
							<li class="menu-item"><a href="#">Return Policy</a></li>
							<li class="menu-item"><a href="#">Privacy Policy</a></li>
							<li class="menu-item"><a href="#">Contact Us</a></li>
						</ul>
					</div>
				</div>
			</div> <!-- /row -->
		</div>
	</footer>
	<div id="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="copyright">
						<div class="row">
							<div class="col-md-6">
								<p>© RE - WEAR Community Clothing Exchange : BY TECH NOVA

							</div>
							<div class="col-md-6">
								<div class="social-links align-right">
									<ul>
										<li>
											<a href="#"><i class="icon icon-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon icon-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon icon-youtube-play"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon icon-behance-square"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div><!--grid-->
				</div><!--footer-bottom-content-->
			</div>
		</div>
	</div>
	<script src="js/jquery-1.11.0.min.js"></script>
	<script crossorigin="anonymous" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
	<script>
		function toggleUserDetails(userId) {
			const box = document.getElementById('user-details-' + userId);
			box.style.display = box.style.display === 'none' || box.style.display === '' ? 'block' : 'none';
		}
	</script>

</body>

</html>
