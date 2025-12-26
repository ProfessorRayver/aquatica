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
        :root { 
            --deep-ocean: #000b1a; 
            --midnight-blue: #011628; 
            --electric-blue: #0066ff; 
            --neon-cyan: #00d4ff; 
            --text-light: #e0e6ed; 
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #000b1a 0%, #011628 50%, #001a33 100%);
            color: var(--text-light); 
            overflow-x: hidden;
            position: relative;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0, 100, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 212, 255, 0.1) 0%, transparent 50%);
            animation: bgShift 15s ease-in-out infinite;
            z-index: -1;
        }
        
        @keyframes bgShift {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        /* Glassmorphism Navbar */
        .navbar { 
            background: rgba(1, 22, 40, 0.7); 
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background: rgba(1, 22, 40, 0.95);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }
        
        .navbar-brand { 
            color: white !important; 
            font-weight: 700; 
            font-size: 1.5rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }
        
        .nav-link { 
            color: #a0b1c5 !important; 
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--neon-cyan);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover {
            color: var(--neon-cyan) !important;
            transform: translateY(-2px);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .btn-book { 
            background: linear-gradient(135deg, var(--electric-blue), #0056b3); 
            border: none; 
            color: white; 
            border-radius: 50px; 
            padding: 10px 30px; 
            font-weight: 600; 
            box-shadow: 0 0 20px rgba(0, 123, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-book::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-book:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 30px rgba(0, 212, 255, 0.6);
        }
        
        /* Hero Section */
        .hero-section { 
            min-height: 90vh; 
            background: 
                linear-gradient(135deg, rgba(0, 11, 26, 0.7), rgba(1, 22, 40, 0.8)),
                url('https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=1920&q=80'); 
            background-size: cover; 
            background-attachment: fixed;
            background-position: center;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(to top, var(--deep-ocean), transparent);
        }
        
        .hero-content {
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-title {
            font-size: 4rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            color: white; 
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.6);
            margin-bottom: 1.5rem;
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { text-shadow: 0 0 20px rgba(0, 212, 255, 0.4); }
            to { text-shadow: 0 0 40px rgba(0, 212, 255, 0.8), 0 0 60px rgba(0, 100, 255, 0.4); }
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        
        /* Glass Card Design */
        .card { 
            background: var(--glass-bg);
            backdrop-filter: blur(10px) saturate(180%);
            border: 1px solid var(--glass-border);
            border-radius: 20px; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none; 
        }
        
        .card:hover {
            transform: translateY(-15px) scale(1.02);
            border-color: var(--neon-cyan);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(0, 212, 255, 0.3),
                inset 0 0 20px rgba(0, 212, 255, 0.1);
        }
        
        .card:hover::before {
            opacity: 1;
        }

        .card-body {
            position: relative;
            z-index: 2;
        }
        
        .card-img-wrapper {
            height: 240px;
            overflow: hidden;
            position: relative;
        }
        
        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .card:hover .card-img-wrapper img {
            transform: scale(1.1) rotate(2deg);
        }
        
        .price-tag { 
            font-size: 1.5rem; 
            color: var(--neon-cyan); 
            font-weight: 700;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        
        /* Section Styling */
        section {
            position: relative;
            padding: 5rem 0;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, var(--neon-cyan), transparent);
            border-radius: 2px;
        }
        
        /* Footer */
        footer { 
            background: rgba(0, 11, 20, 0.95); 
            padding: 4rem 0; 
            border-top: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
        }
        
        /* Scroll Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Loading Animation */
        .loading-wave {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            opacity: 0.1;
            z-index: -1;
        }
        
        .loading-wave path {
            fill: var(--neon-cyan);
            animation: wave 3s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { d: path("M0,50 Q25,40 50,50 T100,50 V100 H0 Z"); }
            50% { d: path("M0,50 Q25,60 50,50 T100,50 V100 H0 Z"); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .section-title { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#">⚓ ALL CITY AQUATICS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#classes">Classes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#shop">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact & Policies</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-book" href="#classes">Book Online</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container hero-content">
            <h1 class="hero-title">Learn Together<br>Grow Together</h1>
            <p class="hero-subtitle">Premium Lifeguard Training • Luxury Amenity Employment</p>
            <a href="#classes" class="btn btn-book px-5 py-3">Get Certified <i class="bi bi-arrow-right ms-2"></i></a>
        </div>
    </header>

    <section id="classes">
        <div class="container">
            <h2 class="text-white text-center section-title fade-in">Upcoming Classes</h2>
            <div class="row g-4">
                <?php
                $stmt = $pdo->query("SELECT * FROM classes");
                while ($row = $stmt->fetch()) { ?>
                <div class="col-md-4 fade-in">
                    <div class="card h-100">
                        <div class="card-img-wrapper">
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['category']); ?></span>
                                <span class="price-tag">$<?php echo $row['price']; ?></span>
                            </div>
                            <h4 class="text-white mb-3"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p style="color:#a0b1c5; flex-grow: 1;"><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="booking.php?class_id=<?php echo $row['id']; ?>" class="btn btn-outline-info w-100 mt-3">
                                Book Now <i class="bi bi-calendar-check ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <section id="shop" style="background: rgba(1, 21, 43, 0.5);">
        <div class="container">
            <h2 class="text-white mb-4 section-title fade-in">ACA <span style="color:var(--neon-cyan)">Gear Shop</span></h2>
            <div class="row g-4">
                <?php
                $stmt_prod = $pdo->query("SELECT * FROM products LIMIT 4");
                while ($prod = $stmt_prod->fetch()) {
                    $stock = isset($prod['stock']) ? $prod['stock'] : 0;
                    $is_out_of_stock = $stock <= 0;
                ?>
                <div class="col-6 col-md-3 fade-in">
                    <div class="card h-100 border-0">
                        <div style="height: 200px; background: white; padding: 20px; display: flex; align-items: center; justify-content: center;">
                            <img src="<?php echo $prod['image_url']; ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <div class="card-body text-center d-flex flex-column">
                            <h6 class="card-title text-white mb-2"><?php echo htmlspecialchars($prod['name']); ?></h6>
                            <p class="price-tag mb-2">$<?php echo $prod['price']; ?></p>
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
                                    <button type="submit" class="btn btn-sm btn-outline-light w-100">
                                        Buy Now <i class="bi bi-cart-plus ms-1"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; 2025 All City Aquatics. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
        
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
        
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>