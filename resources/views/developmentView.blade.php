<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الموقع تحت الصيانة</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; color: #2d3748; }
        .box { background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; max-width: 400px; }
        h1 { margin: 0 0 1rem; color: #1a202c; }
        p { color: #718096; margin-bottom: 2rem; }
        #timer { font-size: 2rem; font-weight: bold; color: #3182ce; letter-spacing: 2px; }
        
        /* CSS Animation للـ SVG */
        .gear { animation: spin 4s linear infinite; transform-origin: center; display: inline-block; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div class="box">
        <div class="gear">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#3182ce" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
        </div>

        <h1>قيد التطوير</h1>
        <p>نقوم ببعض التحديثات من فضلك انتظر قليلا.</p>
        <div id="timer" >00:00:00</div>
    </div>

    <script>
        var startHourIndex = 13; 
        var durationMinutes = 60;
        var startHour = new Date();
        startHour.setHours(startHourIndex, 0, 0, 0);
        var targetDate = new Date(startHour.getTime() + (durationMinutes * 60 * 1000)).getTime();

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = targetDate - now;
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("timer").innerHTML = String(hours).padStart(2, '0') + ":" + String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
            if (distance < 0) { clearInterval(x); location.reload(); }
        }, 1000);
    </script>
</body>
</html>