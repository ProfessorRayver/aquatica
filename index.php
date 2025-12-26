<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) { die("Database Connection Failed."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All City Aquatics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root { --deep-ocean: #001f3f; --midnight-blue: #022c5e; --electric-blue: #007bff; --neon-cyan: #00d4ff; --text-light: #e0e6ed; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--deep-ocean); color: var(--text-light); }
        .navbar { background: rgba(2, 44, 94, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .navbar-brand { color: white !important; font-weight: 700; }
        .nav-link { color: #a0b1c5 !important; transition: 0.3s; }
        .nav-link:hover { color: var(--neon-cyan) !important; }
        .btn-book { background: linear-gradient(45deg, var(--electric-blue), #0056b3); border: none; color: white; border-radius: 50px; padding: 8px 25px; font-weight: 600; box-shadow: 0 0 15px rgba(0, 123, 255, 0.4); }
        .hero-section { height: 85vh; background: linear-gradient(to bottom, rgba(0, 31, 63, 0.5), var(--deep-ocean)), url('https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-attachment: fixed; display: flex; align-items: center; justify-content: center; text-align: center; }
        .card { background-color: var(--midnight-blue); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; transition: 0.3s; }
        .card:hover { transform: translateY(-8px); border-color: var(--neon-cyan); box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        .price-tag { font-size: 1.4rem; color: var(--neon-cyan); font-weight: 700; }
        footer { background-color: #000b14; padding: 4rem 0; border-top: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">ALL CITY AQUATICS</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#classes">Classes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#shop">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-book" href="#classes">Book Online</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <h1 style="font-size: 3.5rem; font-weight: 700; text-transform: uppercase; color: white; text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);">Learn Together<br>Grow Together</h1>
            <p class="lead text-light">Premium Lifeguard Training • Luxury Amenity Employment</p>
            <a href="#classes" class="btn btn-book px-4 py-2 mt-3">Get Certified</a>
        </div>
    </header>

    <section id="classes" class="py-5">
        <div class="container">
            <h2 class="text-white text-center mb-5">Upcoming Classes</h2>
            <div class="row g-4">
                <?php
                $stmt = $pdo->query("SELECT * FROM classes");
                while ($row = $stmt->fetch()) { ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div style="height: 220px; background: url('<?php echo $row['image_url']; ?>') center/cover;"></div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['category']); ?></span>
                                <span class="price-tag">$<?php echo $row['price']; ?></span>
                            </div>
                            <h4 class="text-white"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p style="color:#a0b1c5"><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="booking.php?class_id=<?php echo $row['id']; ?>" class="btn btn-outline-info w-100 mt-auto">Book Now</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section id="shop" class="py-5" style="background-color: #01152b;">
        <div class="container">
            <h2 class="text-white mb-4">ACA <span style="color:var(--neon-cyan)">Gear Shop</span></h2>
            <div class="row g-4">
                <?php
                $stmt_prod = $pdo->query("SELECT * FROM products LIMIT 4");
                while ($prod = $stmt_prod->fetch()) {
                    $stock = isset($prod['stock']) ? $prod['stock'] : 0;
                    $is_out_of_stock = $stock <= 0;
                ?>
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0">
                        <div style="height: 200px; background: white url('<?php echo $prod['image_url']; ?>') center/contain no-repeat;"></div>
                        <div class="card-body text-center d-flex flex-column">
                            <h6 class="card-title text-white"><?php echo htmlspecialchars($prod['name']); ?></h6>
                            <p class="price-tag mb-1">$<?php echo $prod['price']; ?></p>
                            <?php if ($is_out_of_stock): ?>
                                <span class="badge bg-danger mb-3">Sold Out</span>
                                <button class="btn btn-sm btn-secondary w-100 mt-auto" disabled>Out of Stock</button>
                            <?php else: ?>
                                <small class="text-muted mb-3"><?php echo $stock; ?> items left</small>
                                <form action="order.php" method="GET" class="mt-auto">
                                    <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                    <div class="input-group input-group-sm mb-2">
                                        <span class="input-group-text bg-dark text-white border-secondary">Qty</span>
                                        <input type="number" name="quantity" class="form-control bg-dark text-white border-secondary text-center" value="1" min="1" max="<?php echo $stock; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-light w-100">Buy Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section id="contact" class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-5" style="background: rgba(2, 44, 94, 0.5);">
                        <h2 class="text-center text-white mb-4">Contact Us</h2>
                        <form action="send_email.php" method="POST">
                            <div class="mb-3"><label class="text-light">Name</label><input type="text" name="name" class="form-control" required></div>
                            <div class="mb-3"><label class="text-light">Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="mb-3"><label class="text-light">Message</label><textarea name="message" class="form-control" rows="4" required></textarea></div>
                            <button type="submit" name="send" class="btn btn-book w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer><div class="container text-center text-muted">&copy; 2024 All City Aquatics.</div></footer>
</body>
</html>