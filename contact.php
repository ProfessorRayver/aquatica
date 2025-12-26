<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
try { $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); } catch (\PDOException $e) { die("DB Error"); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact & Policies - All City Aquatics</title>
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
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #000b1a 0%, #011628 50%, #001a33 100%);
            color: var(--text-light); 
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
            font-weight: 700; 
            color: white !important; 
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
        }
        
        .nav-link:hover { 
            color: var(--neon-cyan) !important; 
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            color: var(--neon-cyan) !important;
            font-weight: 600;
        }
        
        .btn-book { 
            background: linear-gradient(135deg, var(--electric-blue), #0056b3); 
            border: none; 
            color: white; 
            border-radius: 50px; 
            padding: 8px 25px; 
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 30px rgba(0, 212, 255, 0.6);
        }

        /* Page Header */
        .page-header { 
            padding: 140px 0 60px; 
            text-align: center;
            background: radial-gradient(circle at center, rgba(0, 100, 255, 0.1) 0%, transparent 70%);
        }
        
        .page-header h1 {
            font-size: 3rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.4);
            margin-bottom: 1rem;
            animation: fadeInUp 0.6s ease;
        }
        
        .page-header p {
            font-size: 1.2rem;
            color: #a0b1c5;
            animation: fadeInUp 0.6s ease 0.2s both;
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

        /* Content Cards */
        .content-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }
        
        .content-panel:hover {
            border-color: rgba(0, 212, 255, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.4);
        }
        
        .content-panel h2 {
            color: white;
            font-weight: 700;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(0, 212, 255, 0.3);
        }
        
        .content-panel h2 i {
            color: var(--neon-cyan);
            font-size: 1.8rem;
        }

        /* Form Styles */
        .form-label { 
            font-weight: 600; 
            margin-bottom: 0.5rem; 
            color: #fff;
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
            border-radius: 10px;
            padding: 0.8rem 1rem;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
            color: white !important;
        }
        
        .form-control::placeholder {
            color: #6c7a89;
        }
        
        .btn-send {
            background: linear-gradient(135deg, var(--electric-blue), var(--neon-cyan));
            color: white;
            font-weight: 700;
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            border: none;
            margin-top: 1rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);
        }
        
        .btn-send:hover { 
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 212, 255, 0.5);
        }

        /* Contact Info */
        .contact-info {
            background: rgba(0, 100, 255, 0.1);
            border: 1px solid rgba(0, 100, 255, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: center;
        }
        
        .contact-info p {
            margin-bottom: 0.5rem;
            color: #a0b1c5;
        }
        
        .contact-link {
            color: var(--neon-cyan) !important;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            margin: 0.25rem;
        }
        
        .contact-link:hover {
            background: rgba(0, 212, 255, 0.2);
            transform: translateX(5px);
        }

        /* Policy Sections */
        .policy-section { 
            margin-bottom: 2rem; 
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            border-left: 4px solid var(--neon-cyan);
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .policy-section:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }
        
        .policy-title { 
            color: var(--neon-cyan); 
            font-weight: 700; 
            margin-bottom: 1rem; 
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .policy-title i {
            font-size: 1.3rem;
        }
        
        .policy-text { 
            color: #d0e0f0; 
            font-size: 0.95rem; 
            line-height: 1.7; 
        }
        
        .policy-text p {
            margin-bottom: 0.8rem;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .policy-text p::before {
            content: '→';
            position: absolute;
            left: 0;
            color: var(--neon-cyan);
            font-weight: bold;
        }
        
        .no-refunds { 
            color: #ff6b6b; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 0.9rem; 
            margin-top: 1rem; 
            display: inline-block;
            background: rgba(255, 107, 107, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 100px 0 40px;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .content-panel {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">⚓ ALL CITY AQUATICS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#classes">Classes</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#shop">Shop</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact & Policies</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-book" href="index.php#classes">Book Online</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container page-header">
        <h1><i class="bi bi-chat-dots me-3"></i>Contact & Policies</h1>
        <p>We're here to help with any questions or concerns</p>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            
            <div class="col-lg-6">
                <div class="content-panel">
                    <h2>
                        <i class="bi bi-envelope-open"></i>
                        Send us a Message
                    </h2>
                    
                    <form action="send_email.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-person"></i>
                                Your Name
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i>
                                Email Address
                            </label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-chat-text"></i>
                                Your Message
                            </label>
                            <textarea name="message" class="form-control" rows="6" placeholder="How can we help you?" required></textarea>
                        </div>
                        <button type="submit" name="send" class="btn btn-send">
                            <i class="bi bi-send me-2"></i>Send Message
                        </button>
                    </form>

                    <div class="contact-info">
                        <p class="mb-2"><strong>Or reach us directly:</strong></p>
                        <a href="mailto:info@allcityaquatics.com" class="contact-link">
                            <i class="bi bi-envelope"></i>
                            info@allcityaquatics.com
                        </a>
                        <a href="tel:3324001625" class="contact-link">
                            <i class="bi bi-telephone"></i>
                            (332) 400-1625
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="content-panel">
                    <h2>
                        <i class="bi bi-file-text"></i>
                        Booking Policies
                    </h2>

                    <div class="policy-section">
                        <div class="policy-title">
                            <i class="bi bi-heart-pulse"></i>
                            CPR / Lifeguard Renewal Classes
                        </div>
                        <div class="policy-text">
                            <p>A student must attend their day(s) of class to avoid paying a rescheduling fee.</p>
                            <p>If a student does not attend or pass their class and they fail to reschedule within a month after their original class date, they will need to pay for a new class.</p>
                            <span class="no-refunds">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                There are no refunds
                            </span>
                        </div>
                    </div>

                    <div class="policy-section">
                        <div class="policy-title">
                            <i class="bi bi-life-preserver"></i>
                            Lifeguard Classes
                        </div>
                        <div class="policy-text">
                            <p>A student must attend all 3 days of class to avoid paying a rescheduling fee.</p>
                            <p>If a student does not pass the 3 day class a <strong style="color: var(--neon-cyan);">second chance in a future class will be given free of charge</strong>. Only one second chance is given and it must be within the following month of original class date.</p>
                            <p>If a student does not finish or pass their class and they fail to reschedule within a month after their original class date they will need to pay for a new class.</p>
                            <span class="no-refunds">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                There are no refunds
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>