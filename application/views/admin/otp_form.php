<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin OTP | Loan Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;font-family:'Poppins',sans-serif;}body{margin:0;min-height:100vh;background:#f5f6fa;display:grid;place-items:center;padding:22px;color:#1f2937}.card{width:100%;max-width:430px;background:#fff;border-radius:22px;padding:34px;box-shadow:0 24px 70px rgba(15,23,42,.12)}h1{margin:0 0 8px;font-size:28px}p{margin:0 0 24px;color:#64748b}label{display:block;font-size:13px;font-weight:600;margin-bottom:8px}input{width:100%;border:1px solid #dbe3ef;border-radius:12px;padding:14px 15px;font-size:15px;outline:none}input:focus{border-color:#1d4ed8;box-shadow:0 0 0 4px rgba(29,78,216,.12)}button{width:100%;border:0;border-radius:12px;background:#1d4ed8;color:#fff;padding:14px 18px;font-weight:700;font-size:15px;cursor:pointer;margin-top:18px}.msg{margin-top:14px;color:#b91c1c;font-size:14px}a{display:inline-block;margin-top:18px;color:#1d4ed8;text-decoration:none;font-weight:600}
    </style>
</head>
<body>
    <main class="card">
        <h1>Verify Admin OTP</h1>
        <p>OTP sent to <?php echo html_escape($masked_mobile ?? 'your mobile'); ?>. Test OTP is 000000.</p>
        <form id="otpForm" method="post" action="<?php echo base_url('admin/verify-otp'); ?>">
            <label>Enter OTP</label>
            <input type="text" name="otp" value="000000" required>
            <button type="submit">Verify & Login</button>
        </form>
        <div class="msg" id="otpMessage"></div>
        <a href="<?php echo base_url('admin'); ?>">Back to Login</a>
    </main>
    <script>
        document.getElementById('otpForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var form = event.target;
            fetch(form.action, {method: 'POST', body: new FormData(form)})
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.redirect_url) { window.location.href = data.redirect_url; return; }
                    document.getElementById('otpMessage').textContent = data.error || 'Invalid OTP';
                })
                .catch(function () { document.getElementById('otpMessage').textContent = 'Invalid OTP'; });
        });
    </script>
</body>
</html>
