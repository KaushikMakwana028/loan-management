<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Register OTP | Kreditmitraa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #063d32;
            --ink: #101828;
            --muted: #667085;
            --line: #dce6f0;
            --bg: #f6f8fb;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 18px;
            background: radial-gradient(circle at 0 0, rgba(6, 61, 50, .14), transparent 30%), radial-gradient(circle at 100% 8%, rgba(197, 155, 39, .14), transparent 28%), var(--bg);
            color: var(--ink);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .card {
            width: min(100%, 430px);
            border: 1px solid rgba(220, 230, 240, .95);
            border-radius: 28px;
            background: rgba(255, 255, 255, .94);
            box-shadow: 0 28px 70px rgba(15, 23, 42, .12);
            padding: clamp(24px, 5vw, 36px);
        }

        .icon {
            width: 56px;
            height: 56px;
            border-radius: 19px;
            display: grid;
            place-items: center;
            background: #eef8f4;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .icon svg {
            width: 26px;
            height: 26px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 32px;
            line-height: 1.1;
            letter-spacing: -.03em;
        }

        p {
            margin: 0 0 24px;
            color: var(--muted);
            line-height: 1.65;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 800;
        }

        input {
            width: 100%;
            min-height: 56px;
            border: 1px solid var(--line);
            border-radius: 17px;
            padding: 0 16px;
            text-align: center;
            letter-spacing: .26em;
            font-size: 20px;
            font-weight: 800;
            outline: none;
            background: #fff;
            transition: border-color .16s, box-shadow .16s;
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(6, 61, 50, .12);
        }

        button {
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 16px;
            margin-top: 18px;
            background: linear-gradient(135deg, var(--primary), #c59b27);
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(6, 61, 50, .22);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: var(--primary);
            font-size: 14px;
            font-weight: 800;
        }

        @media(max-width:520px) {
            body {
                align-items: start;
                padding-top: 34px;
            }

            .card {
                border-radius: 24px;
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    <main class="card">
        <div style="text-align: center; margin-bottom: 24px;">
            <img src="<?php echo base_url('assets/images/logo/logo.jpeg'); ?>" alt="Logo" style="height: 72px; width: auto; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
        </div>
        <h1>Verify Registration OTP</h1>
        <p>OTP sent to <?php echo html_escape($masked_mobile ?? 'your mobile'); ?>.</p>
        <form id="otpForm" method="post" action="<?php echo base_url('user/register-verify-otp'); ?>">
            <label>Enter OTP</label>
            <input type="text" name="otp" inputmode="numeric" maxlength="6" placeholder="Enter OTP" required>
            <button type="submit">Verify & Register</button>
        </form>
        <a class="back" href="<?php echo base_url('register'); ?>">Back to Register</a>
    </main>
    <script>
        document.getElementById('otpForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var form = event.target;
            fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                        return;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Invalid OTP'
                    });
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Invalid OTP'
                    });
                });
        });
    </script>
</body>

</html>