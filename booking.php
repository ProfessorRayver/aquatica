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
    <title>Schedule Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #000; color: white; font-family: 'Arial', sans-serif; }
        .scheduler-container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .section-title { border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.2rem; font-weight: bold; }
        .calendar-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center; }
        .calendar-day-header { font-size: 0.8rem; color: #888; padding: 10px 0; }
        .calendar-day { padding: 15px 0; cursor: pointer; border-radius: 4px; transition: 0.2s; }
        .calendar-day:hover { background-color: #222; }
        .calendar-day.selected { background-color: #00aaff; color: black; font-weight: bold; }
        .calendar-day.empty { pointer-events: none; }
        .calendar-day.today { border: 1px solid #00aaff; }
        .nav-btn { background: transparent; border: 1px solid #444; color: white; padding: 5px 10px; border-radius: 4px; }
        .nav-btn:hover { background: #222; border-color: white; }
        .time-slot { display: block; width: 100%; background: transparent; border: 1px solid #444; color: white; padding: 10px; margin-bottom: 10px; border-radius: 0; transition: 0.2s; }
        .time-slot:hover { border-color: #888; }
        .time-slot.selected { background-color: #00aaff; border-color: #00aaff; color: black; font-weight: bold; }
        .service-details { background-color: #000; padding-left: 20px; }
        .btn-next { background-color: #00aaff; color: black; width: 100%; border: none; font-weight: bold; padding: 12px; margin-top: 20px; }
        .btn-next:hover { background-color: #33bbff; }
        #clientForm { display: none; }
        .form-control { background: #111; border: 1px solid #333; color: white; }
        .form-control:focus { background: #111; color: white; border-color: #00aaff; }
    </style>
</head>
<body>

<div class="scheduler-container">
    <div class="row">
        <div class="col-12 mb-4"><h1>Schedule your service</h1><p class="text-secondary"><?php echo $class_title; ?></p></div>
    </div>
    <div class="row" id="step1">
        <div class="col-md-5">
            <div class="calendar-header-row section-title">
                <button class="nav-btn" onclick="changeMonth(-1)">&lt;</button>
                <span id="monthYearLabel" style="font-size: 1.1rem; font-weight: bold;">Month Year</span>
                <button class="nav-btn" onclick="changeMonth(1)">&gt;</button>
            </div>
            <div class="calendar-grid" id="calendarGrid"></div>
        </div>
        <div class="col-md-3">
            <div class="section-title">Availability</div>
            <p class="small text-secondary" id="selectedDateLabel">Select a date</p>
            <button class="btn time-slot" onclick="selectTime(this, '9:00 am')">9:00 am</button>
            <button class="btn time-slot" onclick="selectTime(this, '10:00 am')">10:00 am</button>
            <button class="btn time-slot" onclick="selectTime(this, '11:00 am')">11:00 am</button>
            <button class="btn time-slot" onclick="selectTime(this, '1:00 pm')">1:00 pm</button>
            <button class="btn time-slot" onclick="selectTime(this, '2:00 pm')">2:00 pm</button>
        </div>
        <div class="col-md-4 service-details">
            <div class="section-title">Service Details</div>
            <h5 class="mb-1"><?php echo $class_title; ?></h5>
            <div class="mt-5 border-top border-secondary pt-3">
                <div class="d-flex justify-content-between"><span>Date:</span><span id="summaryDate" class="text-info">--</span></div>
                <div class="d-flex justify-content-between"><span>Time:</span><span id="summaryTime" class="text-info">--</span></div>
            </div>
            <button class="btn-next" onclick="showForm()">Next</button>
        </div>
    </div>

    <div class="row justify-content-center" id="clientForm">
        <div class="col-md-6">
            <div class="section-title">Enter Your Details</div>
            <form action="process_booking.php" method="POST">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <input type="hidden" name="class_name" value="<?php echo $class_title; ?>">
                <input type="hidden" name="date" id="formDate">
                <input type="hidden" name="time" id="formTime">
                <div class="mb-3"><label>Full Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label>Phone</label><input type="tel" name="phone" class="form-control" required></div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-light w-50" onclick="goBack()">Back</button>
                    <button type="submit" name="submit_booking" class="btn btn-next w-50 mt-0">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
        weekDays.forEach(day => { const div = document.createElement("div"); div.className = "calendar-day-header"; div.innerText = day; grid.appendChild(div); });
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        for (let i = 0; i < firstDay; i++) { const div = document.createElement("div"); div.className = "calendar-day empty"; grid.appendChild(div); }
        for (let d = 1; d <= daysInMonth; d++) {
            const div = document.createElement("div"); div.className = "calendar-day"; div.innerText = d;
            if (selectedDateObj && d === selectedDateObj.getDate() && month === selectedDateObj.getMonth() && year === selectedDateObj.getFullYear()) div.classList.add("selected");
            const today = new Date();
            if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) div.classList.add("today");
            div.onclick = () => selectDate(d, month, year);
            grid.appendChild(div);
        }
    }
    function changeMonth(dir) { currentDate.setMonth(currentDate.getMonth() + dir); renderCalendar(); }
    function selectDate(d, m, y) {
        selectedDateObj = new Date(y, m, d);
        renderCalendar();
        const dateString = selectedDateObj.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('selectedDateLabel').innerText = dateString;
        document.getElementById('summaryDate').innerText = dateString;
        const ym = String(m + 1).padStart(2, '0'); const dd = String(d).padStart(2, '0');
        document.getElementById('formDate').value = `${y}-${ym}-${dd}`;
    }
    function selectTime(btn, time) {
        document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('selected'));
        btn.classList.add('selected');
        selectedTime = time;
        document.getElementById('summaryTime').innerText = time;
        document.getElementById('formTime').value = time;
    }
    function showForm() {
        if(!document.getElementById('formDate').value || !document.getElementById('formTime').value) { alert("Please select date and time"); return; }
        document.getElementById('step1').style.display = 'none'; document.getElementById('clientForm').style.display = 'flex';
    }
    function goBack() { document.getElementById('clientForm').style.display = 'none'; document.getElementById('step1').style.display = 'flex'; }
    renderCalendar();
</script>
</body>
</html>