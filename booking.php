<?php
$host = 'localhost'; $db = 'all_city_aquatics'; $user = 'root'; $pass = '';
try { $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass); } catch (\PDOException $e) { die("DB Error"); }

$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : 0;
$class_title = "Select a Service";

if ($class_id) {
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$class_id]);
    $class = $stmt->fetch();
    if ($class) $class_title = $class['title'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Service - All City Aquatics</title>
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
        .scheduler-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 100px 20px 60px;
        }
        
        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.6s ease;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 30px rgba(0, 212, 255, 0.3);
        }
        
        .page-header .service-name {
            color: var(--neon-cyan);
            font-size: 1.3rem;
            font-weight: 600;
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
            height: 100%;
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
        
        /* Calendar Styles */
        .calendar-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .nav-btn {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.3);
            color: var(--neon-cyan);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .nav-btn:hover {
            background: rgba(0, 212, 255, 0.2);
            border-color: var(--neon-cyan);
            transform: scale(1.05);
        }
        
        #monthYearLabel {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            text-align: center;
        }
        
        .calendar-day-header {
            font-size: 0.85rem;
            color: var(--neon-cyan);
            padding: 10px 0;
            font-weight: 600;
        }
        
        .calendar-day {
            padding: 15px 8px;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .calendar-day::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .calendar-day:hover::before {
            left: 100%;
        }
        
        .calendar-day:hover {
            background: rgba(0, 212, 255, 0.1);
            border-color: var(--neon-cyan);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.2);
        }
        
        .calendar-day.selected {
            background: linear-gradient(135deg, var(--electric-blue), var(--neon-cyan));
            color: white;
            font-weight: 700;
            border-color: var(--neon-cyan);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
        }
        
        .calendar-day.empty {
            pointer-events: none;
            opacity: 0.3;
        }
        
        .calendar-day.today {
            border: 2px solid var(--neon-cyan);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(0, 212, 255, 0); }
        }
        
        /* Time Slots */
        .time-slots-container {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .time-slots-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .time-slots-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        
        .time-slots-container::-webkit-scrollbar-thumb {
            background: var(--neon-cyan);
            border-radius: 10px;
        }
        
        .time-slot {
            display: block;
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem;
            margin-bottom: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .time-slot::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--neon-cyan);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .time-slot:hover {
            border-color: var(--neon-cyan);
            background: rgba(0, 212, 255, 0.1);
            transform: translateX(5px);
        }
        
        .time-slot:hover::before {
            transform: scaleY(1);
        }
        
        .time-slot.selected {
            background: linear-gradient(135deg, var(--electric-blue), var(--neon-cyan));
            border-color: var(--neon-cyan);
            color: white;
            font-weight: 700;
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);
        }
        
        .time-slot.selected::before {
            transform: scaleY(1);
        }
        
        /* Service Details */
        .service-details {
            position: sticky;
            top: 100px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #a0b1c5;
            font-weight: 500;
        }
        
        .detail-value {
            color: var(--neon-cyan);
            font-weight: 600;
        }
        
        /* Buttons */
        .btn-next {
            background: linear-gradient(135deg, var(--electric-blue), var(--neon-cyan));
            color: white;
            width: 100%;
            border: none;
            font-weight: 700;
            padding: 1rem;
            margin-top: 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.3);
        }
        
        .btn-next:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 212, 255, 0.5);
        }
        
        .btn-next:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Form Styles */
        #clientForm {
            display: none;
            animation: fadeInUp 0.6s ease;
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
        
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        }
        
        .form-control::placeholder {
            color: #6c7a89;
        }
        
        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        /* Progress Indicator */
        .progress-indicator {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .progress-step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        
        .progress-step.active {
            background: linear-gradient(135deg, var(--electric-blue), var(--neon-cyan));
            border-color: var(--neon-cyan);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
        }
        
        .progress-line {
            flex: 1;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            align-self: center;
            position: relative;
        }
        
        .progress-line.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            background: var(--neon-cyan);
            animation: progress 0.6s ease;
        }
        
        @keyframes progress {
            from { width: 0; }
            to { width: 100%; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .scheduler-container {
                padding: 80px 15px 40px;
            }
            
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .section-card {
                margin-bottom: 1.5rem;
            }
            
            .service-details {
                position: static;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">⚓ ALL CITY AQUATICS</a>
            <a href="index.php" class="btn-back">
                <i class="bi bi-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </nav>

    <div class="scheduler-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="bi bi-calendar-check me-2"></i>Schedule Your Service</h1>
            <p class="service-name"><?php echo htmlspecialchars($class_title); ?></p>
        </div>

        <!-- Progress Indicator -->
        <div class="progress-indicator" id="progressBar">
            <div class="progress-step active" id="step1Indicator">1</div>
            <div class="progress-line" id="line1"></div>
            <div class="progress-step" id="step2Indicator">2</div>
        </div>

        <!-- Step 1: Date & Time Selection -->
        <div class="row g-4" id="step1">
            <div class="col-lg-5">
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-calendar3"></i>
                        Select Date
                    </div>
                    <div class="calendar-header-row">
                        <button class="nav-btn" onclick="changeMonth(-1)">
                            <i class="bi bi-chevron-left"></i> Prev
                        </button>
                        <span id="monthYearLabel">Month Year</span>
                        <button class="nav-btn" onclick="changeMonth(1)">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    <div class="calendar-grid" id="calendarGrid"></div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-clock"></i>
                        Available Times
                    </div>
                    <p class="small mb-3" style="color: #a0b1c5;" id="selectedDateLabel">
                        <i class="bi bi-info-circle me-2"></i>Select a date first
                    </p>
                    <div class="time-slots-container">
                        <button class="btn time-slot" onclick="selectTime(this, '9:00 am')">
                            <i class="bi bi-clock me-2"></i>9:00 am
                        </button>
                        <button class="btn time-slot" onclick="selectTime(this, '10:00 am')">
                            <i class="bi bi-clock me-2"></i>10:00 am
                        </button>
                        <button class="btn time-slot" onclick="selectTime(this, '11:00 am')">
                            <i class="bi bi-clock me-2"></i>11:00 am
                        </button>
                        <button class="btn time-slot" onclick="selectTime(this, '1:00 pm')">
                            <i class="bi bi-clock me-2"></i>1:00 pm
                        </button>
                        <button class="btn time-slot" onclick="selectTime(this, '2:00 pm')">
                            <i class="bi bi-clock me-2"></i>2:00 pm
                        </button>
                        <button class="btn time-slot" onclick="selectTime(this, '3:00 pm')">
                            <i class="bi bi-clock me-2"></i>3:00 pm
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="section-card service-details">
                    <div class="section-title">
                        <i class="bi bi-file-text"></i>
                        Booking Summary
                    </div>
                    <h5 class="mb-4" style="color: white;"><?php echo htmlspecialchars($class_title); ?></h5>
                    
                    <div class="detail-row">
                        <span class="detail-label"><i class="bi bi-calendar3 me-2"></i>Date:</span>
                        <span id="summaryDate" class="detail-value">Not selected</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="bi bi-clock me-2"></i>Time:</span>
                        <span id="summaryTime" class="detail-value">Not selected</span>
                    </div>
                    
                    <button class="btn-next" onclick="showForm()" id="nextBtn" disabled>
                        Continue to Details <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Client Information Form -->
        <div class="row justify-content-center" id="clientForm">
            <div class="col-lg-8">
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-person-fill"></i>
                        Your Information
                    </div>
                    <form action="process_booking.php" method="POST">
                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                        <input type="hidden" name="class_name" value="<?php echo htmlspecialchars($class_title); ?>">
                        <input type="hidden" name="date" id="formDate">
                        <input type="hidden" name="time" id="formTime">
                        
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person me-2"></i>Full Name
                                </label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-phone me-2"></i>Phone Number
                                </label>
                                <input type="tel" name="phone" class="form-control" placeholder="+1 (555) 000-0000" required>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 mt-4">
                            <button type="button" class="btn btn-back w-50" onclick="goBack()">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </button>
                            <button type="submit" name="submit_booking" class="btn-next w-50 mt-0">
                                <i class="bi bi-check-circle me-2"></i>Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentDate = new Date();
        let selectedDateObj = null;
        let selectedTime = '';
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            document.getElementById("monthYearLabel").innerText = `${months[month]} ${year}`;
            const grid = document.getElementById("calendarGrid");
            grid.innerHTML = "";
            
            weekDays.forEach(day => {
                const div = document.createElement("div");
                div.className = "calendar-day-header";
                div.innerText = day;
                grid.appendChild(div);
            });
            
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            for (let i = 0; i < firstDay; i++) {
                const div = document.createElement("div");
                div.className = "calendar-day empty";
                grid.appendChild(div);
            }
            
            for (let d = 1; d <= daysInMonth; d++) {
                const div = document.createElement("div");
                div.className = "calendar-day";
                div.innerText = d;
                
                if (selectedDateObj && d === selectedDateObj.getDate() && month === selectedDateObj.getMonth() && year === selectedDateObj.getFullYear()) {
                    div.classList.add("selected");
                }
                
                const today = new Date();
                if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    div.classList.add("today");
                }
                
                div.onclick = () => selectDate(d, month, year);
                grid.appendChild(div);
            }
            
            checkButtonState();
        }

        function changeMonth(dir) {
            currentDate.setMonth(currentDate.getMonth() + dir);
            renderCalendar();
        }

        function selectDate(d, m, y) {
            selectedDateObj = new Date(y, m, d);
            renderCalendar();
            const dateString = selectedDateObj.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('selectedDateLabel').innerHTML = `<i class="bi bi-check-circle me-2"></i>${dateString}`;
            document.getElementById('summaryDate').innerText = dateString;
            const ym = String(m + 1).padStart(2, '0');
            const dd = String(d).padStart(2, '0');
            document.getElementById('formDate').value = `${y}-${ym}-${dd}`;
            checkButtonState();
        }

        function selectTime(btn, time) {
            document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('selected'));
            btn.classList.add('selected');
            selectedTime = time;
            document.getElementById('summaryTime').innerText = time;
            document.getElementById('formTime').value = time;
            checkButtonState();
        }

        function checkButtonState() {
            const nextBtn = document.getElementById('nextBtn');
            if (document.getElementById('formDate').value && document.getElementById('formTime').value) {
                nextBtn.disabled = false;
            } else {
                nextBtn.disabled = true;
            }
        }

        function showForm() {
            if (!document.getElementById('formDate').value || !document.getElementById('formTime').value) {
                alert("Please select both date and time");
                return;
            }
            document.getElementById('step1').style.display = 'none';
            document.getElementById('clientForm').style.display = 'flex';
            document.getElementById('step1Indicator').classList.remove('active');
            document.getElementById('step2Indicator').classList.add('active');
            document.getElementById('line1').classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goBack() {
            document.getElementById('clientForm').style.display = 'none';
            document.getElementById('step1').style.display = 'flex';
            document.getElementById('step1Indicator').classList.add('active');
            document.getElementById('step2Indicator').classList.remove('active');
            document.getElementById('line1').classList.remove('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        renderCalendar();
    </script>
</body>
</html>