<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
try { $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); } catch (\PDOException $e) { die("DB Error"); }

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$initial_qty = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($product_id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $prod = $stmt->fetch();
}
if (!$prod) { header("Location: index.php"); exit; }

$price = $prod['price'];
$delivery_fee = 20.00;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ACA Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://www.paypal.com/sdk/js?client-id=AXa2yZbyGHtToJq-Dl5m8ShrXtUkbnNd19HJCGdvs5JioOXwEm2xLHLejgh-JOxgTOPIMFVhZuwd3lCu&currency=USD"></script>
    <style>
        :root {
            --deep-ocean: #000b1a;
            --midnight-blue: #011628;
            --electric-blue: #0066ff;
            --neon-cyan: #00d4ff;
            --text-light: #e0e6ed;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: linear-gradient(135deg, #000b1a 0%, #011628 50%, #001a33 100%);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(1, 22, 40, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-5px);
            color: white;
        }
        
        /* Container */
        .checkout-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 100px 20px 60px;
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 3rem;
            animation: fadeInDown 0.6s ease;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Section Cards */
        .section-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            border-color: rgba(0, 212, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(0, 212, 255, 0.3);
        }
        
        .section-title i {
            color: var(--neon-cyan);
            font-size: 1.5rem;
        }
        
        /* Product Card */
        .product-card {
            display: flex;
            gap: 2rem;
            align-items: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(0, 212, 255, 0.3);
        }
        
        .product-img {
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 12px;
            padding: 15px;
            object-fit: contain;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-info h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-size: 1.8rem;
            color: var(--neon-cyan);
            font-weight: 700;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }
        
        /* Quantity Selector */
        .qty-selector {
            display: inline-flex;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            overflow: hidden;
            margin-top: 1rem;
        }
        
        .qty-btn {
            background: transparent;
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .qty-btn:hover {
            background: rgba(0, 212, 255, 0.2);
        }
        
        .qty-input {
            background: transparent;
            border: none;
            color: white;
            width: 60px;
            text-align: center;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        /* Form Styles */
        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-label i {
            color: var(--neon-cyan);
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        }
        
        .form-control::placeholder {
            color: #6c7a89;
        }
        
        /* Order Summary */
        .summary-card {
            background: linear-gradient(135deg, rgba(0, 100, 255, 0.1), rgba(0, 212, 255, 0.05));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            position: sticky;
            top: 100px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #a0b1c5;
            font-weight: 500;
        }
        
        .summary-value {
            color: white;
            font-weight: 600;
        }
        
        .summary-total {
            font-size: 1.5rem;
            color: var(--neon-cyan);
            font-weight: 700;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
        }
        
        /* Buttons */
        .btn-checkout {
            background: linear-gradient(135deg, var(--electric-blue), var(--neon-cyan));
            color: white;
            width: 100%;
            padding: 1rem;
            border: none;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);
        }
        
        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 212, 255, 0.5);
        }
        
        .btn-apple {
            background: #000;
            color: white;
            border: 1px solid #333;
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            cursor: not-allowed;
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .btn-apple:hover {
            opacity: 0.8;
        }
        
        #paypal-button-container {
            margin-bottom: 1rem;
        }
        
        /* Payment Icons */
        .payment-methods {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .payment-icon {
            width: 50px;
            height: 35px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .payment-icon:hover {
            opacity: 1;
            transform: translateY(-3px);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .checkout-container {
                padding: 80px 15px 40px;
            }
            
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .product-card {
                flex-direction: column;
                text-align: center;
            }
            
            .summary-card {
                position: static;
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">⚓ ALL CITY AQUATICS</a>
            <a href="index.php#shop" class="btn-back">
                <i class="bi bi-arrow-left me-2"></i>Back to Shop
            </a>
        </div>
    </nav>

    <div class="checkout-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="bi bi-cart-check"></i>
                Checkout
            </h1>
        </div>

        <form action="process_order.php" method="POST" id="checkoutForm">
            <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($prod['name']); ?>">
            <input type="hidden" name="total_price" id="hiddenTotalPrice" value="">
            <input type="hidden" name="payment_status" id="paymentStatus" value="Pending">
            
            <div class="row">
                <!-- Left Column: Cart & Shipping -->
                <div class="col-lg-7">
                    <!-- Cart Item -->
                    <div class="section-card">
                        <div class="section-title">
                            <i class="bi bi-bag-check"></i>
                            Your Order
                        </div>
                        
                        <div class="product-card">
                            <img src="<?php echo $prod['image_url']; ?>" class="product-img" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                            <div class="product-info">
                                <h4><?php echo htmlspecialchars($prod['name']); ?></h4>
                                <p class="product-price">$<?php echo number_format($prod['price'], 2); ?></p>
                                
                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="qty-selector">
                                        <button type="button" class="qty-btn" onclick="updateQty(-1)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="quantity" id="qtyInput" class="qty-input" value="<?php echo $initial_qty; ?>" readonly>
                                        <button type="button" class="qty-btn" onclick="updateQty(1)">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div>
                                        <span style="color: #a0b1c5;">Subtotal: </span>
                                        <span id="itemTotalDisplay" class="product-price">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Details -->
                    <div class="section-card">
                        <div class="section-title">
                            <i class="bi bi-truck"></i>
                            Shipping Information
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Full Name
                                </label>
                                <input type="text" name="name" id="inputName" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="john@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt"></i>
                                    Shipping Address
                                </label>
                                <textarea name="address" id="inputAddress" class="form-control" rows="3" placeholder="123 Main Street, City, State, ZIP Code" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-5">
                    <div class="summary-card">
                        <div class="section-title">
                            <i class="bi bi-receipt"></i>
                            Order Summary
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span id="subtotalDisplay" class="summary-value">$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Shipping & Handling</span>
                            <span class="summary-value">$<?php echo number_format($delivery_fee, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label" style="font-size: 1.2rem; color: white;">Total</span>
                            <span id="finalTotalDisplay" class="summary-total">$0.00</span>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" name="submit_order" class="btn btn-checkout">
                                <i class="bi bi-cash me-2"></i>Pay Cash on Delivery
                            </button>
                            <div id="paypal-button-container"></div>
                            <button type="button" class="btn btn-apple" onclick="alert('Apple Pay requires a real domain.')">
                                <i class="bi bi-apple me-2"></i>Apple Pay
                            </button>
                        </div>
                        
                        <div class="payment-methods">
                            <div class="payment-icon"><i class="bi bi-credit-card-2-front text-dark"></i></div>
                            <div class="payment-icon"><i class="bi bi-paypal text-primary"></i></div>
                            <div class="payment-icon"><i class="bi bi-apple text-dark"></i></div>
                            <div class="payment-icon"><i class="bi bi-shield-check text-success"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const unitPrice = <?php echo $price; ?>;
        const deliveryFee = <?php echo $delivery_fee; ?>;
        const maxStock = <?php echo $prod['stock']; ?>;
        let currentTotal = 0;

        function updateQty(change) {
            let el = document.getElementById('qtyInput');
            let newQty = parseInt(el.value) + change;
            if (newQty < 1) newQty = 1;
            if (newQty > maxStock) { 
                alert("Maximum stock available: " + maxStock); 
                newQty = maxStock; 
            }
            el.value = newQty;
            recalculate();
        }

        function recalculate() {
            let qty = parseInt(document.getElementById('qtyInput').value);
            let sub = unitPrice * qty;
            let total = sub + deliveryFee;
            currentTotal = total.toFixed(2);
            
            document.getElementById('itemTotalDisplay').innerText = '$' + sub.toFixed(2);
            document.getElementById('subtotalDisplay').innerText = '$' + sub.toFixed(2);
            document.getElementById('finalTotalDisplay').innerText = '$' + total.toFixed(2);
            document.getElementById('hiddenTotalPrice').value = currentTotal;
        }
        
        recalculate();

        paypal.Buttons({
            style: { 
                layout: 'vertical', 
                color: 'gold', 
                shape: 'rect', 
                label: 'paypal',
                height: 48
            },
            onInit: function(data, actions) {
                actions.disable();
                document.querySelector('#checkoutForm').addEventListener('change', function(event) {
                    if (document.getElementById('inputName').value && 
                        document.getElementById('inputEmail').value && 
                        document.getElementById('inputAddress').value) {
                        actions.enable();
                    } else { 
                        actions.disable(); 
                    }
                });
            },
            onClick: function() {
                if (!document.getElementById('inputName').value) {
                    alert('Please fill in all shipping details first.');
                }
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: currentTotal },
                        description: '<?php echo htmlspecialchars($prod['name']); ?>'
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    document.getElementById('paymentStatus').value = "Paid via PayPal";
                    const form = document.getElementById('checkoutForm');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'submit_order';
                    hiddenInput.value = 'true';
                    form.appendChild(hiddenInput);
                    form.submit();
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>