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
    <title>Checkout - ACA Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <script src="https://www.paypal.com/sdk/js?client-id=AXa2yZbyGHtToJq-Dl5m8ShrXtUkbnNd19HJCGdvs5JioOXwEm2xLHLejgh-JOxgTOPIMFVhZuwd3lCu&currency=USD"></script>

    <style>
        body { background-color: #001f3f; color: #fff; font-family: 'Segoe UI', sans-serif; }
        h2, h4, h5, label, span, div { color: #ffffff !important; }
        .text-secondary { color: #b0c4de !important; }
        .form-control { background-color: #0d2a4d; border: 1px solid #4a6fa5; color: #ffffff !important; font-weight: 500; }
        .form-control:focus { background-color: #0d2a4d; border-color: #00d4ff; color: white; box-shadow: 0 0 5px rgba(0, 212, 255, 0.5); }
        .cart-item-container { border-bottom: 1px solid #3a4b60; padding-bottom: 2rem; margin-bottom: 2rem; }
        .product-img { background: white; border-radius: 8px; width: 120px; height: 120px; object-fit: contain; padding: 10px; }
        .qty-selector { display: inline-flex; border: 1px solid #fff; border-radius: 4px; overflow: hidden; }
        .qty-btn { background: transparent; border: none; color: white; padding: 5px 15px; cursor: pointer; }
        .qty-btn:hover { background: rgba(255,255,255,0.2); }
        .qty-input { background: transparent; border: none; color: white; width: 40px; text-align: center; font-weight: bold; }
        .btn-checkout { background-color: #00aaff; color: #001f3f; font-weight: bold; width: 100%; padding: 15px; border: none; font-size: 1.1rem; margin-bottom: 15px; }
        .btn-checkout:hover { background-color: #33bbff; color: #000; }
        .btn-apple { background-color: black; color: white; border: 1px solid #444; width: 100%; padding: 12px; margin-bottom: 15px; cursor: not-allowed; opacity: 0.7; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row mb-4"><div class="col-12"><h2>My cart</h2></div></div>

    <form action="process_order.php" method="POST" id="checkoutForm">
        <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($prod['name']); ?>">
        <input type="hidden" name="total_price" id="hiddenTotalPrice" value="">
        <input type="hidden" name="payment_status" id="paymentStatus" value="Pending">
        
        <div class="row">
            <div class="col-lg-7 pe-lg-5">
                <div class="d-flex cart-item-container align-items-start">
                    <img src="<?php echo $prod['image_url']; ?>" class="product-img shadow-sm">
                    <div class="ms-4 flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($prod['name']); ?></h4>
                                <h5 class="mb-2">$<?php echo number_format($prod['price'], 2); ?></h5>
                            </div>
                            <a href="index.php" class="text-white fs-5"><i class="bi bi-trash3"></i></a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="qty-selector">
                                <button type="button" class="qty-btn" onclick="updateQty(-1)">−</button>
                                <input type="number" name="quantity" id="qtyInput" class="qty-input" value="<?php echo $initial_qty; ?>" readonly>
                                <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                            </div>
                            <h5 id="itemTotalDisplay">$0.00</h5>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="mb-3">Shipping Details</h4>
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Full Name</label><input type="text" name="name" id="inputName" class="form-control" placeholder="Enter your name" required></div>
                        <div class="col-12"><label class="form-label">Email Address</label><input type="email" name="email" id="inputEmail" class="form-control" placeholder="name@example.com" required></div>
                        <div class="col-12"><label class="form-label">Shipping Address</label><textarea name="address" id="inputAddress" class="form-control" rows="2" placeholder="Street, City, Zip" required></textarea></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-4" style="background: rgba(255,255,255,0.05); border-radius: 8px;">
                    <h4 class="mb-4">Order summary</h4>
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span id="subtotalDisplay">$0.00</span></div>
                    <div class="d-flex justify-content-between mb-3"><span>Delivery</span><span>$<?php echo number_format($delivery_fee, 2); ?></span></div>
                    <div class="d-flex justify-content-between border-top pt-3 mb-4"><h4>Total</h4><h4 id="finalTotalDisplay">$0.00</h4></div>
                    
                    <button type="submit" name="submit_order" class="btn btn-checkout">Pay Cash / Manual</button>
                    <div id="paypal-button-container"></div>
                    <button type="button" class="btn btn-apple" onclick="alert('Apple Pay requires a real domain.')"><i class="bi bi-apple me-1"></i> Pay</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const unitPrice = <?php echo $price; ?>;
    const deliveryFee = <?php echo $delivery_fee; ?>;
    const maxStock = <?php echo $prod['stock']; ?>;
    let currentTotal = 0;

    function updateQty(change) {
        let el = document.getElementById('qtyInput');
        let newQty = parseInt(el.value) + change;
        if (newQty < 1) newQty = 1;
        if (newQty > maxStock) { alert("Max stock reached!"); newQty = maxStock; }
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
        style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'checkout' },
        onInit: function(data, actions) {
            actions.disable();
            document.querySelector('#checkoutForm').addEventListener('change', function(event) {
                if (document.getElementById('inputName').value && document.getElementById('inputEmail').value && document.getElementById('inputAddress').value) {
                    actions.enable();
                } else { actions.disable(); }
            });
        },
        onClick: function() {
            if (!document.getElementById('inputName').value) alert('Please fill in your Shipping Details first.');
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{ amount: { value: currentTotal } }]
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